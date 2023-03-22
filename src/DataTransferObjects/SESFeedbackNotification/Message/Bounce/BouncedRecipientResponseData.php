<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects\SESFeedbackNotification\Message\Bounce;

use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class BouncedRecipientResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class BouncedRecipientResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $emailAddress;

    /**
     * @var string
     */
    public string $status;

    /**
     * @var string
     */
    public string $action;

    /**
     * @var string
     */
    public string $diagnosticCode;
}
