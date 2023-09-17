<?php

namespace Luchavez\AwsSesBounce\DataFactories;

use Illuminate\Database\Eloquent\Builder;
use Luchavez\AwsSesBounce\Models\EmailAddress;
use Luchavez\StarterKit\Abstracts\BaseDataFactory;

/**
 * Class EmailAddressDataFactory
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmailAddressDataFactory extends BaseDataFactory
{
    /**
     * @return string[]
     */
    public function getUniqueKeys(): array
    {
        return [
            'email_address',
        ];
    }

    /**
     * @var string
     */
    public string $email_address;

    /**
     * @return Builder
     *
     * @example User::query()
     */
    public function getBuilder(): Builder
    {
        return EmailAddress::query();
    }
}
