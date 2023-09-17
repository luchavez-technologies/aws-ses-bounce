<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message;

use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Mail\CommonHeadersResponseData;
use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Mail\HeadersResponseData;
use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class MailResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MailResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $timestamp;

    /**
     * @var string
     */
    public string $messageId;

    /**
     * @var string
     */
    public string $source;

    /**
     * @var string
     */
    public string $sourceArn;

    /**
     * @var string
     */
    public string $sourceIp;

    /**
     * @var string
     */
    public string $sendingAccountId;

    /**
     * @var string
     */
    public string $callerIdentity;

    /**
     * @var array
     */
    public array $destination = [];

    /**
     * Note: When bounce notifications are not configured to include the original email headers,
     * the mail object within the notifications does not include the `headersTruncated`, `headers`, and `commonHeaders` fields.
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-examples.html#notification-examples-bounce
     */

    /**
     * @var bool|null
     */
    public ?bool $headersTruncated;

    /**
     * @var HeadersResponseData[]|null
     */
    public ?array $headers;

    /**
     * @var CommonHeadersResponseData|null
     */
    public ?CommonHeadersResponseData $commonHeaders;

    /**
     * @param  array|null  $headers
     */
    public function setHeaders(?array $headers): void
    {
        $this->headers = collect($headers)->map(fn ($header) => HeadersResponseData::from($header))->toArray();
    }

    /**
     * @param  array|null  $commonHeaders
     */
    public function setCommonHeaders(?array $commonHeaders): void
    {
        $this->commonHeaders = CommonHeadersResponseData::from($commonHeaders);
    }
}
