<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Complaint;

use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class ComplainedRecipientResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ComplainedRecipientResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $emailAddress;
}
