<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message;

use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Bounce\BouncedRecipientResponseData;
use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class BounceResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class BounceResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $bounceType;

    /**
     * @var string
     */
    public string $reportingMTA;

    /**
     * @var BouncedRecipientResponseData[]
     */
    public array $bouncedRecipients = [];

    /**
     * @var string
     */
    public string $bounceSubType;

    /**
     * @var string
     */
    public string $timestamp;

    /**
     * @var string
     */
    public string $feedbackId;

    /**
     * @var string
     */
    public string $remoteMtaIp;

    /**
     * @param  BouncedRecipientResponseData[]  $bouncedRecipients
     */
    public function setBouncedRecipients(array $bouncedRecipients): void
    {
        $this->bouncedRecipients = collect($bouncedRecipients)->map(fn ($recipient) => BouncedRecipientResponseData::from($recipient))->toArray();
    }
}
