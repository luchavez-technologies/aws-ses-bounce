<?php

namespace Luchavez\AwsSesBounce\Listeners;

use Luchavez\AwsSesBounce\Exceptions\EmptyToRecipientsException;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Arr;

/**
 * Class ValidateEmailAddressListener
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ValidateEmailAddressListener
{
    /**
     * Handle the event.
     *
     * @param  MessageSending  $event
     * @return void
     *
     * @throws EmptyToRecipientsException
     */
    public function handle(MessageSending $event): void
    {
        // Get recipients
        $to_recipients = $event->message->getTo() ?? [];
        $cc_recipients = $event->message->getCc() ?? [];
        $bcc_recipients = $event->message->getBcc() ?? [];

        // Get unique ones from all recipients
        $unique_recipients = collect(array_merge($to_recipients, $cc_recipients, $bcc_recipients))->keys()->unique();

        // Separate recipients into accepted and rejected (bounced)
        $result = awsSesBounce()->filterBouncedEmails($unique_recipients);

        // Remove bounced recipients
        $to_recipients = Arr::only($to_recipients, $result['accepted']);
        $cc_recipients = Arr::only($cc_recipients, $result['accepted']);
        $bcc_recipients = Arr::only($bcc_recipients, $result['accepted']);

        // Throw error if no recipient left
        if (! count($to_recipients)) {
            throw new EmptyToRecipientsException(Arr::only($result, 'rejected'));
        }

        // Set recipients with bounced ones removed
        if (method_exists($event->message, 'setTo')) {
            $event->message->setTo($to_recipients);
            $event->message->setCc($cc_recipients);
            $event->message->setBcc($bcc_recipients);
        } else {
            $event->message->to(...$to_recipients);
            $event->message->cc(...$cc_recipients);
            $event->message->bcc(...$bcc_recipients);
        }
    }
}
