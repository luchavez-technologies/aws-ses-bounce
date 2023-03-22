<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Mail;

use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class CommonHeadersResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class CommonHeadersResponseData extends BaseJsonSerializable
{
    /**
     * @var array
     */
    public array $from;

    /**
     * @var string
     */
    public string $date;

    /**
     * @var array
     */
    public array $to;

    /**
     * @var string
     */
    public string $messageId;

    /**
     * @var string
     */
    public string $subject;
}
