<?php

namespace Luchavez\AwsSesBounce\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Luchavez\AwsSesBounce\Database\Factories\BounceNotificationFactory;

/**
 * Trait HasBounceNotificationFactoryTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait HasBounceNotificationFactoryTrait
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return BounceNotificationFactory::new();
    }
}
