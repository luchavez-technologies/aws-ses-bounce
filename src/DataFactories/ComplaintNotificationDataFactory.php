<?php

namespace Luchavez\AwsSesBounce\DataFactories;

use Carbon\Carbon;
use Luchavez\AwsSesBounce\Models\ComplaintNotification;
use Luchavez\StarterKit\Abstracts\BaseDataFactory;
// Model
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ComplaintNotificationDataFactory
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ComplaintNotificationDataFactory extends BaseDataFactory
{
    /**
     * @var int
     */
    public int $email_address_id;

    /**
     * @var string
     */
    public string $user_agent;

    /**
     * @var string
     */
    public string $feedback_type;

    /**
     * @var Carbon
     */
    public Carbon $arrival_date;

    /**
     * @var Carbon
     */
    public Carbon $complained_at;

    /**
     * @return Builder
     *
     * @example User::query()
     */
    public function getBuilder(): Builder
    {
        return ComplaintNotification::query();
    }

    /**
     * @param  Carbon|string  $arrival_date
     */
    public function setArrivalDate(Carbon|string $arrival_date): void
    {
        $this->arrival_date = $arrival_date instanceof Carbon ? $arrival_date : Carbon::parse($arrival_date);
    }

    /**
     * @param  Carbon|string  $complained_at
     */
    public function setComplainedAt(Carbon|string $complained_at): void
    {
        $this->complained_at = $complained_at instanceof Carbon ? $complained_at : Carbon::parse($complained_at);
    }
}
