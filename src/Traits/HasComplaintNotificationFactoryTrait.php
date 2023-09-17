<?php

namespace Luchavez\AwsSesBounce\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Luchavez\AwsSesBounce\Database\Factories\ComplaintNotificationFactory;

/**
 * Trait HasComplaintNotificationFactoryTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait HasComplaintNotificationFactoryTrait
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ComplaintNotificationFactory::new();
    }
}
