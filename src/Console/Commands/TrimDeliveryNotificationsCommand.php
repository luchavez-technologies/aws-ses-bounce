<?php

namespace Luchavez\AwsSesBounce\Console\Commands;

use Illuminate\Console\Command;
use Luchavez\AwsSesBounce\Models\DeliveryNotification;
use Luchavez\StarterKit\Traits\UsesCommandCustomMessagesTrait;

/**
 * Class TrimDeliveryNotificationsCommand
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class TrimDeliveryNotificationsCommand extends Command
{
    use UsesCommandCustomMessagesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'asb:deliveries:trim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trim delivery notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $max_date = now()->subDays(awsSesBounce()->getDeliveryNotificationMaxAgeInDays());

        $soft_delete = awsSesBounce()->shouldSoftDeleteNotifications();

        $this->note(($soft_delete ? 'Soft-deleting' : 'Deleting').' delivery notifications before '.$max_date->toDateString().'...');

        $builder = DeliveryNotification::query()->where('created_at', '<=', $max_date);

        $delete_count = $builder->count();

        if ($delete_count) {
            awsSesBounce()->shouldSoftDeleteNotifications() ? $builder->delete() : $builder->forceDelete();
            $this->done('Successfully deleted delivery notifications: '.$delete_count);
        } else {
            $this->note('Nothing to delete.');
        }

        return self::SUCCESS;
    }
}
