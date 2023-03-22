<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message;

use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class DeliveryResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeliveryResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $timestamp;

    /**
     * @var array
     */
    public array $recipients = [];

    /**
     * @var string
     */
    public string $processingTimeMillis;

    /**
     * @var string
     */
    public string $reportingMTA;

    /**
     * @var string
     */
    public string $smtpResponse;

    /**
     * @var string
     */
    public string $remoteMtaIp;
}
