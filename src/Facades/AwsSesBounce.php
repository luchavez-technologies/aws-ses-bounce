<?php

namespace Luchavez\AwsSesBounce\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class AwsSesBounce
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @see \Luchavez\AwsSesBounce\Services\AwsSesBounce
 */
class AwsSesBounce extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'aws-ses-bounce';
    }
}
