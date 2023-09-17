<?php

namespace Luchavez\AwsSesBounce\Observers;

use Luchavez\AwsSesBounce\Models\ComplaintNotification;

/**
 * Class ComplaintNotificationObserver
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ComplaintNotificationObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the ComplaintNotification "saved" event.
     *
     * @param  ComplaintNotification  $complaintNotification
     * @return void
     */
    public function saved(ComplaintNotification $complaintNotification): void
    {
        // Get email address and load complaint count
        $email = $complaintNotification->emailAddress;
        $email->loadCount('complaintNotifications');

        // Block email address if exceeds the max complaint count
        if (! $email->isBlocked() && $email->complaint_notifications_count >= ($max = awsSesBounce()->getMaxComplaintCount())) {
            $email->block('Max complaint count reached: '.$max);
        }
    }
}
