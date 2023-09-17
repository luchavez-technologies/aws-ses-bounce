<?php

namespace Luchavez\AwsSesBounce\DataFactories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Luchavez\AwsSesBounce\Models\DeliveryNotification;
use Luchavez\StarterKit\Abstracts\BaseDataFactory;

/**
 * Class DeliveryNotificationDataFactory
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeliveryNotificationDataFactory extends BaseDataFactory
{
    /**
     * @var int
     */
    public int $email_address_id;

    /**
     * @var int
     */
    public int $processing_time_millis;

    /**
     * @var string
     */
    public string $smtp_response;

    /**
     * @var Carbon
     */
    public Carbon $delivered_at;

    /**
     * @return Builder
     *
     * @example User::query()
     */
    public function getBuilder(): Builder
    {
        return DeliveryNotification::query();
    }

    /**
     * @param  Carbon|string  $delivered_at
     */
    public function setDeliveredAt(Carbon|string $delivered_at): void
    {
        $this->delivered_at = $delivered_at instanceof Carbon ? $delivered_at : Carbon::parse($delivered_at);
    }
}
