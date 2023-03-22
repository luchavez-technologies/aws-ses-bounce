<?php

namespace Luchavez\AwsSesBounce\Services;

use Luchavez\AwsSesBounce\DataFactories\BounceNotificationDataFactory;
use Luchavez\AwsSesBounce\DataFactories\ComplaintNotificationDataFactory;
use Luchavez\AwsSesBounce\DataFactories\DeliveryNotificationDataFactory;
use Luchavez\AwsSesBounce\DataFactories\EmailAddressDataFactory;
use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Bounce\BouncedRecipientResponseData;
use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Complaint\ComplainedRecipientResponseData;
use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotificationResponseData;
use Luchavez\AwsSesBounce\DataTransferObjects\SNSTopicSubscriptionConfirmationResponseData;
use Luchavez\AwsSesBounce\Exceptions\EmptyToRecipientsException;
use Luchavez\AwsSesBounce\Models\BounceNotification;
use Luchavez\AwsSesBounce\Models\ComplaintNotification;
use Luchavez\AwsSesBounce\Models\DeliveryNotification;
use Luchavez\AwsSesBounce\Models\EmailAddress;
use Luchavez\ApiSdkKit\Models\AuditLog;
use Luchavez\StarterKit\Traits\HasTaggableCacheTrait;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class AwsSesBounce
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @since 2022-07-13
 */
class AwsSesBounce
{
    use HasTaggableCacheTrait;

    /**
     * @return string
     */
    public function getMainTag(): string
    {
        return 'aws-ses-bounce';
    }

    /**
     * @param  bool  $rehydrate
     * @return Collection
     */
    public function getBlockedEmails(bool $rehydrate = false): Collection
    {
        $key = 'blocked-emails';

        return $this->getCache([], $key, fn () => EmailAddress::blocked()->pluck('email_address'), $rehydrate);
    }

    /**
     * @return array
     */
    public function getApiMiddleware(): array
    {
        $middleware = config('aws-ses-bounce.api_middleware');

        if (is_string($middleware)) {
            return explode(',', $middleware);
        }

        return $middleware;
    }

    /**
     * @param  Collection|array  $emails
     * @return array[]
     */
    #[ArrayShape(['accepted' => 'array', 'rejected' => 'array'])]
    public function filterBouncedEmails(Collection|array $emails): array
    {
        $emails = collect($emails);

        $rejected = $emails->intersect($this->getBlockedEmails());

        $accepted = $emails->diff($rejected);

        return [
            'accepted' => $accepted->toArray(),
            'rejected' => $rejected->toArray(),
        ];
    }

    /***** TEST EMAIL RELATED *****/

    /**
     * @return bool
     */
    public function isEmailTestApiEnabled(): bool
    {
        return config('aws-ses-bounce.email_test_api_enabled') && ! App::isProduction();
    }

    /**
     * @param  string  $message
     * @param  string|array|null  $to
     * @param  string|array|null  $cc
     * @param  string|array|null  $bcc
     * @return void
     *
     * @throws EmptyToRecipientsException
     */
    public function sendTestEmail(string $message, string|array|null $to, string|array|null $cc = [], string|array|null $bcc = []): void
    {
        $to = Arr::wrap($to);
        $cc = Arr::wrap($cc);
        $bcc = Arr::wrap($bcc);

        if (count($to)) {
            Mail::raw($message, function (Message $message) use ($to, $cc, $bcc) {
                $message->subject('Hello World!')->to($to)->cc($cc)->bcc($bcc);
            });
        } else {
            throw new EmptyToRecipientsException();
        }
    }

    /***** MAX FEEDBACK COUNT *****/

    /**
     * @return int
     */
    public function getMaxBounceCount(): int
    {
        return config('aws-ses-bounce.max_bounce_count');
    }

    /***** BLOCK / UNBLOCK *****/

    /**
     * @param  EmailAddress|string  $emailAddress
     * @param  string  $reason
     * @return EmailAddress
     */
    public function block(EmailAddress|string $emailAddress, string $reason): EmailAddress
    {
        if ($emailAddress instanceof EmailAddress) {
            $model = $emailAddress;
        } else {
            $model = EmailAddress::query()->where('email_address', $emailAddress)->first();
        }

        // Create email address if blank
        if (! $model) {
            $model = EmailAddressDataFactory::from(['email_address' => $emailAddress])->create();
        }

        $model->block($reason);

        return $model;
    }

    /**
     * @param  EmailAddress|string  $emailAddress
     * @param  string  $reason
     * @return EmailAddress
     */
    public function unblock(EmailAddress|string $emailAddress, string $reason): EmailAddress
    {
        if (is_string($emailAddress)) {
            $emailAddress = EmailAddress::query()->where('email_address', $emailAddress)->firstOrFail();
        }

        $emailAddress->unblock($reason);

        return $emailAddress;
    }

    /***** DATA DUMPING RELATED *****/

    /**
     * @return bool
     */
    public function isDumpApiEnabled(): bool
    {
        return config('aws-ses-bounce.dump.enabled') && ! App::isProduction();
    }

    /**
     * @return string
     */
    public function getDumpApiUrl(): string
    {
        return config('aws-ses-bounce.dump.url');
    }

    /**
     * @param  mixed  $request
     * @return AuditLog|PromiseInterface|Builder|Response|\Illuminate\Http\Response|null
     */
    public function dumpData(mixed $request): \Illuminate\Http\Response|AuditLog|Builder|PromiseInterface|Response|null
    {
        return makeRequest(awsSesBounce()->getDumpApiUrl())->data($request)->post('api/aws-ses/dump');
    }

    /***** VALIDATE SIGNATURE RELATED *****/

    /**
     * @return bool
     */
    public function shouldValidateSignature(): bool
    {
        return config('aws-ses-bounce.validate_signature');
    }

    /**
     * @param  Request|FormRequest  $request
     * @param  bool  $absolute
     * @return void
     */
    public function validateSignature(Request|FormRequest $request, bool $absolute = true): void
    {
        if ($this->shouldValidateSignature() && ! $request->hasValidSignature($absolute)) {
            throw new InvalidSignatureException();
        }
    }

    /***** BOUNCE REASONS *****/

    /**
     * @param  bool  $rehydrate
     * @return Collection
     */
    public function getBounceReasons(bool $rehydrate = false): Collection
    {
        $key = 'bounce-reasons';

        return $this->getCache([], $key, function () {
            return collect(config('aws-ses-bounce.bounce_reasons'))->map(function ($reason, $status_code) {
                $reason['status_code'] = $status_code;

                return $reason;
            });
        }, $rehydrate);
    }

    /***** NOTIFICATION TRIMMING RELATED *****/

    /**
     * @return bool
     */
    public function shouldSoftDeleteNotifications(): bool
    {
        return config('aws-ses-bounce.soft_delete_notifications');
    }

    /**
     * @return int
     */
    public function getDeliveryNotificationMaxAgeInDays(): int
    {
        return config('aws-ses-bounce.deliveries_max_age_in_days');
    }

    /***** PROCESS SNS NOTIFICATIONS *****/

    /**
     * @param  Request|FormRequest  $request
     * @return bool
     */
    public function confirmSnsTopicSubscription(Request|FormRequest $request): bool
    {
        $data = SNSTopicSubscriptionConfirmationResponseData::from(json_decode($request->getContent(), true));

        if (isset($data->subscribeURL)) {
            $log = makeRequest($data->subscribeURL)->executeGet();

            return $log->successful();
        }

        return false;
    }

    /**
     * @param  Request|FormRequest  $request
     * @return BounceNotification[]
     */
    public function createBounceNotifications(Request|FormRequest $request): array
    {
        $bounce_notifications = [];

        $feedback = SESFeedbackNotificationResponseData::from(json_decode($request->getContent(), true));

        if (isset($feedback->message->bounce)) {
            // Prepare BounceNotificationDataFactory
            $bounce_notification_factory = new BounceNotificationDataFactory();
            $bounce_notification_factory->type = $feedback->message->bounce->bounceType;
            $bounce_notification_factory->sub_type = $feedback->message->bounce->bounceSubType;
            $bounce_notification_factory->source_ip = $feedback->message->mail->sourceIp;
            $bounce_notification_factory->setBouncedAt($feedback->message->bounce->timestamp);

            // Prepare EmailAddressDataFactory
            $email_address_factory = new EmailAddressDataFactory();

            collect($feedback->message->bounce->bouncedRecipients)
                ->each(function (BouncedRecipientResponseData $recipient) use (&$bounce_notifications, $bounce_notification_factory, $email_address_factory) {
                    // Fill-in EmailAddressDataFactory
                    $email_address_factory->email_address = $recipient->emailAddress;

                    // Create or find EmailAddress model
                    $email_address = $email_address_factory->firstOrCreate();

                    // Create BounceNotification model
                    $bounce_notification_factory->email_address_id = $email_address->id;
                    $bounce_notification_factory->status_code = $recipient->status;
                    $bounce_notifications[] = $bounce_notification_factory->create();
                });
        }

        return $bounce_notifications;
    }

    /**
     * @param  Request|FormRequest  $request
     * @return ComplaintNotification[]
     */
    public function createComplaintNotifications(Request|FormRequest $request): array
    {
        $complaint_notifications = [];

        $feedback = SESFeedbackNotificationResponseData::from(json_decode($request->getContent(), true));

        if (isset($feedback->message->complaint)) {
            // Prepare ComplaintNotificationDataFactory()
            $complaint_notification_factory = new ComplaintNotificationDataFactory();
            $complaint_notification_factory->user_agent = $feedback->message->complaint->userAgent;
            $complaint_notification_factory->feedback_type = $feedback->message->complaint->complaintFeedbackType;
            $complaint_notification_factory->setArrivalDate($feedback->message->complaint->arrivalDate);
            $complaint_notification_factory->setComplainedAt($feedback->message->complaint->timestamp);

            // Prepare EmailAddressDataFactory
            $email_address_factory = new EmailAddressDataFactory();

            collect($feedback->message->complaint->complainedRecipients)
                ->each(function (ComplainedRecipientResponseData $recipient) use (&$complaint_notifications, $complaint_notification_factory, $email_address_factory) {
                    // Fill-in EmailAddressDataFactory
                    $email_address_factory->email_address = $recipient->emailAddress;

                    // Create or find EmailAddress model
                    $email_address = $email_address_factory->firstOrCreate();

                    // Create BounceNotification model
                    $complaint_notification_factory->email_address_id = $email_address->id;
                    $complaint_notifications[] = $complaint_notification_factory->create();
                });
        }

        return $complaint_notifications;
    }

    /**
     * @param  Request|FormRequest  $request
     * @return DeliveryNotification[]
     */
    public function createDeliveryNotifications(Request|FormRequest $request): array
    {
        $delivery_notifications = [];

        $feedback = SESFeedbackNotificationResponseData::from(json_decode($request->getContent(), true));

        if (isset($feedback->message->delivery)) {
            // Prepare DeliveryNotificationDataFactory
            $delivery_notification_factory = new DeliveryNotificationDataFactory();
            $delivery_notification_factory->processing_time_millis = $feedback->message->delivery->processingTimeMillis;
            $delivery_notification_factory->smtp_response = $feedback->message->delivery->smtpResponse;
            $delivery_notification_factory->setDeliveredAt($feedback->message->delivery->timestamp);

            // Prepare EmailAddressDataFactory
            $email_address_factory = new EmailAddressDataFactory();

            collect($feedback->message->delivery->recipients)
                ->each(function (string $recipient) use (&$delivery_notifications, $delivery_notification_factory, $email_address_factory) {
                    // Fill-in EmailAddressDataFactory
                    $email_address_factory->email_address = $recipient;

                    // Create or find EmailAddress model
                    $email_address = $email_address_factory->firstOrCreate();

                    // Create BounceNotification model
                    $delivery_notification_factory->email_address_id = $email_address->id;
                    $delivery_notifications[] = $delivery_notification_factory->create();
                });
        }

        return $delivery_notifications;
    }
}
