<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification;

use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\BounceResponseData;
use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\ComplaintResponseData;
use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\DeliveryResponseData;
use Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\MailResponseData;
use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class BaseMessageResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MessageResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $notificationType;

    /**
     * @var MailResponseData
     */
    public MailResponseData $mail;

    /**
     * For Bounce Feedback Type
     *
     * @var BounceResponseData
     */
    public BounceResponseData $bounce;

    /**
     * For Complaint Feedback Type
     *
     * @var ComplaintResponseData
     */
    public ComplaintResponseData $complaint;

    /**
     * For Delivery Feedback Type
     *
     * @var DeliveryResponseData
     */
    public DeliveryResponseData $delivery;

    /**
     * @param  MailResponseData|array  $mail
     */
    public function setMail(MailResponseData|array $mail): void
    {
        $this->mail = $mail instanceof MailResponseData ? $mail : MailResponseData::from($mail);
    }

    /**
     * @param  BounceResponseData|array  $bounce
     */
    public function setBounce(BounceResponseData|array $bounce): void
    {
        $this->bounce = $bounce instanceof BounceResponseData ? $bounce : BounceResponseData::from($bounce);
    }

    /**
     * @param  ComplaintResponseData|array  $complaint
     */
    public function setComplaint(ComplaintResponseData|array $complaint): void
    {
        $this->complaint = $complaint instanceof ComplaintResponseData ? $complaint : ComplaintResponseData::from($complaint);
    }

    /**
     * @param  DeliveryResponseData|array  $delivery
     */
    public function setDelivery(DeliveryResponseData|array $delivery): void
    {
        $this->delivery = $delivery instanceof DeliveryResponseData ? $delivery : DeliveryResponseData::from($delivery);
    }
}
