<?php

namespace Luchavez\AwsSesBounce\Database\Factories;

// Model
use Luchavez\AwsSesBounce\Models\EmailAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

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
