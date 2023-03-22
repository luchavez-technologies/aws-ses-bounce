<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message;

use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Complaint\ComplainedRecipientResponseData;
use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class ComplaintResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ComplaintResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $userAgent;

    /**
     * @var ComplainedRecipientResponseData[]
     */
    public array $complainedRecipients = [];

    /**
     * @var string
     */
    public string $complaintFeedbackType;

    /**
     * @var string
     */
    public string $arrivalDate;

    /**
     * @var string
     */
    public string $timestamp;

    /**
     * @var string
     */
    public string $feedbackId;

    /**
     * @param  ComplainedRecipientResponseData[]  $complainedRecipients
     */
    public function setComplainedRecipients(array $complainedRecipients): void
    {
        $this->complainedRecipients = collect($complainedRecipients)->map(fn ($recipient) => ComplainedRecipientResponseData::from($recipient))->toArray();
    }
}
