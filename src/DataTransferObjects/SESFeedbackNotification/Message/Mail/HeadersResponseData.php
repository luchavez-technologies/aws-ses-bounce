<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Mail;

use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class HeadersResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class HeadersResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $value;
}
