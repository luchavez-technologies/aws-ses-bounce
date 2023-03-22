<?php

namespace Luchavez\AwsSesBounce\Console\Commands;

use Luchavez\StarterKit\Traits\UsesCommandCustomMessagesTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

/**
 * Class GenerateAwsSesBounceSignedURLCommand
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class GenerateAwsSesBounceSignedURLCommand extends Command
{
    use UsesCommandCustomMessagesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'asb:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Signed URLs for AWS SES Bounce.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->note('Feedback Notifications');
        $this->note('Amazon SES will notify a Simple Notification Service topic about bounce, complaint, and/or delivery feedback for this identity.');

        $routes = [
            'Bounce' => 'aws-ses.webhook.bounce',
            'Complaint' => 'aws-ses.webhook.complaint',
            'Delivery' => 'aws-ses.webhook.delivery',
        ];

        $webhooks = collect($routes)->map(function ($route, $title) {
            $route = awsSesBounce()->shouldValidateSignature() ? URL::signedRoute($route) : URL::route($route);

            return [$title, $route];
        })->toArray();

        $this->table(
            ['Feedback Type', 'Endpoint (A web server that can receive notifications from Amazon SNS)'],
            $webhooks
        );

        return self::SUCCESS;
    }
}
