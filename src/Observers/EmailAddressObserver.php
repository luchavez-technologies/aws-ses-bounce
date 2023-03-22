<?php

namespace Luchavez\AwsSesBounce\Observers;

use Luchavez\AwsSesBounce\Models\EmailAddress;

/**
 * Class EmailAddressObserver
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmailAddressObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the EmailAddress "saved" event.
     *
     * @param  EmailAddress  $emailAddress
     * @return void
     */
    public function saved(EmailAddress $emailAddress): void
    {
        awsSesBounce()->getBlockedEmails(true);
    }
}
