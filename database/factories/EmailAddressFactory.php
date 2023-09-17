<?php

namespace Luchavez\AwsSesBounce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Luchavez\AwsSesBounce\Models\EmailAddress;

/**
 * Class EmailAddress
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmailAddressFactory extends Factory
{
    protected $model = EmailAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
