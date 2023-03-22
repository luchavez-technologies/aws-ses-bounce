<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects;

/**
 * Class SNSTopicSubscriptionConfirmationResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class SNSTopicSubscriptionConfirmationResponseData extends SNSNotificationResponseData
{
    /**
     * @var string
     */
    public string $token;

    /**
     * @var string
     */
    public string $message;

    /**
     * @var string
     */
    public string $subscribeURL;
}
