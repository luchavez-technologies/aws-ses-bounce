<?php

namespace Luchavez\AwsSesBounce\Observers;

use Luchavez\AwsSesBounce\Models\DeliveryNotification;

/**
 * Class DeliveryNotificationObserver
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeliveryNotificationObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the DeliveryNotification "saving" event.
     *
     * @param  DeliveryNotification  $deliveryNotification
     * @return void
     */
    public function saving(DeliveryNotification $deliveryNotification): void
    {
        //
    }
}
