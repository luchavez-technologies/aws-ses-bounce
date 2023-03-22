<?php

namespace Luchavez\AwsSesBounce\Observers;

use Luchavez\AwsSesBounce\Models\BounceNotification;

/**
 * Class BounceNotificationObserver
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class BounceNotificationObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the BounceNotification "saving" event.
     *
     * @param  BounceNotification  $bounceNotification
     * @return void
     */
    public function saving(BounceNotification $bounceNotification): void
    {
        // Block email if "Mailbox Does Not Exist"
        if ($bounceNotification->isTypePermanent() && ($bounceNotification->isSubTypeGeneral() || $bounceNotification->isSubTypeNoEmail())) {
            $bounceNotification->emailAddress->block('Automatically blocked by the system: '.$bounceNotification->type.'-'.$bounceNotification->sub_type);
        }
    }

    /**
     * Handle the BounceNotification "saved" event.
     *
     * @param  BounceNotification  $bounceNotification
     * @return void
     */
    public function saved(BounceNotification $bounceNotification): void
    {
        // Get email address and load bounce count
        $email = $bounceNotification->emailAddress;
        $email->loadCount('bounceNotifications');

        // Block email address if exceeds the max bounce count
        if (! $email->isBlocked() && $email->bounce_notifications_count >= ($max = awsSesBounce()->getMaxBounceCount())) {
            $email->block('Max bounce count reached: '.$max);
        }
    }
}
