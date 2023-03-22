<?php

namespace Luchavez\AwsSesBounce\DataTransferObjects;

use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Class SNSNotificationResponseData
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class SNSNotificationResponseData extends BaseJsonSerializable
{
    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $messageId;

    /**
     * @var string
     */
    public string $topicArn;

    /**
     * @var string
     */
    public string $timestamp;

    /**
     * @var string
     */
    public string $signatureVersion;

    /**
     * @var string
     */
    public string $signature;

    /**
     * @var string
     */
    public string $signingCertURL;

    /**
     * @param  array  $array
     * @return $this
     */
    protected function setFields(array $array): static
    {
        // Keys from Subscription Confirmation are capitalized. To be compatible with this DTO, they should be lowercased.
        $array = collect($array)->mapWithKeys(fn ($value, $key) => [lcfirst($key) => $value]);

        $this->getFieldKeys()->each(function ($value) use ($array) {
            if ($array->has($value)) {
                if (method_exists($this, $method = 'set'.$value)) {
                    $this->$method($array->get($value));
                } else {
                    $this->$value = $array->get($value);
                }
            }
        });

        return $this;
    }
}
