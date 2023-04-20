<?php

namespace Luchavez\AwsSesBounce\Http\Controllers;

use App\Http\Controllers\Controller;
use Luchavez\AwsSesBounce\Exceptions\EmptyToRecipientsException;
use Luchavez\AwsSesBounce\Http\Requests\AwsSesBounceTestRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AwsSesBounceController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AwsSesBounceController extends Controller
{
    /**
     * AWS SES Bounce Feedback Webhook
     *
     * @group AWS SES Controller
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function bounce(Request $request): JsonResponse
    {
        awsSesBounce()->validateSignature(request: $request, absolute: false);

        // Check if subscription confirmation
        if (awsSesBounce()->confirmSnsTopicSubscription($request)) {
            return simpleResponse()
                ->data([])
                ->message('Successfully confirmed bounce feedback subscription.')
                ->success()
                ->generate();
        }

        $notifications = awsSesBounce()->createBounceNotifications($request);

        return simpleResponse()
            ->data($notifications)
            ->message('Successfully created records.')
            ->success()
            ->generate();
    }

    /**
     * AWS SES Complaint Feedback Webhook
     *
     * @group AWS SES Controller
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function complaint(Request $request): JsonResponse
    {
        awsSesBounce()->validateSignature(request: $request, absolute: false);

        // Check if subscription confirmation
        if (awsSesBounce()->confirmSnsTopicSubscription($request)) {
            return simpleResponse()
                ->data([])
                ->message('Successfully confirmed complaint feedback subscription.')
                ->success()
                ->generate();
        }

        $notifications = awsSesBounce()->createComplaintNotifications($request);

        return simpleResponse()
            ->data($notifications)
            ->message('Successfully created records.')
            ->success()
            ->generate();
    }

    /**
     * AWS SES Delivery Feedback Webhook
     *
     * @group AWS SES Controller
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function delivery(Request $request): JsonResponse
    {
        awsSesBounce()->validateSignature(request: $request, absolute: false);

        // Check if subscription confirmation
        if (awsSesBounce()->confirmSnsTopicSubscription($request)) {
            return simpleResponse()
                ->data([])
                ->message('Successfully confirmed delivery feedback subscription.')
                ->success()
                ->generate();
        }

        $notifications = awsSesBounce()->createDeliveryNotifications($request);

        return simpleResponse()
            ->data($notifications)
            ->message('Successfully created records.')
            ->success()
            ->generate();
    }

    /**
     * AWS SES Send Email Test
     *
     * @group AWS SES Controller
     *
     * @param  AwsSesBounceTestRequest  $request
     * @return JsonResponse
     *
     * @throws EmptyToRecipientsException
     */
    public function test(AwsSesBounceTestRequest $request): JsonResponse
    {
        $message = $request->get('message', 'This is a test email.');

        awsSesBounce()->sendTestEmail($message, $request->get('to'), $request->get('cc'), $request->get('bcc'));

        return simpleResponse()
            ->data([])
            ->message('Email successfully sent.')
            ->success()
            ->generate();
    }

    /**
     * AWS SES Dump Data
     *
     * @group AWS SES Controller
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function dump(Request $request): JsonResponse
    {
        return simpleResponse()
            ->data($request)
            ->message('Data dumped successfully.')
            ->success()
            ->generate();
    }
}
