<?php

namespace Luchavez\AwsSesBounce\DataFactories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Luchavez\AwsSesBounce\Models\BounceNotification;
use Luchavez\StarterKit\Abstracts\BaseDataFactory;

/**
 * Class BounceNotificationDataFactory
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
 */
class BounceNotificationDataFactory extends BaseDataFactory
{
    /**
     * @var int
     */
    public int $email_address_id;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $sub_type;

    /**
     * @var string
     */
    public string $status_code;

    /**
     * @var string
     */
    public string $source_ip;

    /**
     * @var Carbon
     */
    public Carbon $bounced_at;

    /**
     * @return Builder
     *
     * @example User::query()
     */
    public function getBuilder(): Builder
    {
        return BounceNotification::query();
    }

    /**
     * @param  Carbon|string  $bounced_at
     */
    public function setBouncedAt(Carbon|string $bounced_at): void
    {
        $this->bounced_at = $bounced_at instanceof Carbon ? $bounced_at : Carbon::parse($bounced_at);
    }
}
