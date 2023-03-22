<?php

namespace Luchavez\AwsSesBounce\Providers;

use Luchavez\AwsSesBounce\Console\Commands\GenerateAwsSesBounceSignedURLCommand;
use Luchavez\AwsSesBounce\Console\Commands\TrimDeliveryNotificationsCommand;
use Luchavez\AwsSesBounce\Listeners\ValidateEmailAddressListener;
use Luchavez\AwsSesBounce\Models\BounceNotification;
use Luchavez\AwsSesBounce\Observers\BounceNotificationObserver;
use Luchavez\AwsSesBounce\Services\AwsSesBounce;
use Luchavez\StarterKit\Abstracts\BaseStarterKitServiceProvider;
use Luchavez\StarterKit\Interfaces\ProviderConsoleKernelInterface;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;

/**
 * Class AwsSesBounceServiceProvider
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AwsSesBounceServiceProvider extends BaseStarterKitServiceProvider implements ProviderConsoleKernelInterface
{
    /**
     * @var array|string[]
     */
    protected array $commands = [
        GenerateAwsSesBounceSignedURLCommand::class,
        TrimDeliveryNotificationsCommand::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $observer_map = [
        BounceNotificationObserver::class => BounceNotification::class,
    ];

    /**
     * Publishable Environment Variables
     *
     * @example [ 'HELLO_WORLD' => true ]
     *
     * @var array
     */
    protected array $env_vars = [
        'ASB_EMAIL_TEST_API_ENABLED' => true,
        'ASB_DUMP_ENABLED' => false,
        'ASB_DUMP_URL' => '${APP_URL}',
        'ASB_VALIDATE_SIGNATURE' => false,
        'ASB_MAX_BOUNCE_COUNT' => 3,
        'ASB_SOFT_DELETE_NOTIFICATIONS' => false,
        'ASB_DELIVERIES_MAX_AGE_IN_DAYS' => 7,
    ];

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        Event::listen(MessageSending::class, [ValidateEmailAddressListener::class, 'handle']);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the service the package provides.
        $this->app->singleton('aws-ses-bounce', function ($app) {
            return new AwsSesBounce();
        });

        parent::register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['aws-ses-bounce'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/aws-ses-bounce.php' => config_path('aws-ses-bounce.php'),
        ], 'aws-ses-bounce.config');

        // Registering package commands.
        $this->commands($this->commands);
    }

    /**
     * @param  bool  $is_api
     * @return array
     */
    public function getDefaultRouteMiddleware(bool $is_api): array
    {
        return [];
    }

    /**
     * @param  Schedule  $schedule
     * @return void
     */
    public function registerToConsoleKernel(Schedule $schedule): void
    {
        $schedule->command('asb:deliveries:trim')
            ->daily()
            ->onOneServer()
            ->evenInMaintenanceMode();
    }
}
