<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects;

use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\MessageResponseData;

/**
 * Class SESFeedbackNotificationResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class SESFeedbackNotificationResponseData extends SNSNotificationResponseData
{
    /**
     * @var MessageResponseData
     */
    public MessageResponseData $message;

    /**
     * @var string
     */
    public string $unsubscribeURL;

    /**
     * Since message is obviously an object upon response inspection,
     * it should be decoded into an array for better display.
     *
     * @param  string  $message
     */
    public function setMessage(string $message): void
    {
        $this->message = MessageResponseData::from(json_decode($message, true));
    }
}
