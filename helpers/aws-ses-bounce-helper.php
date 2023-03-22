<?php

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */

use Luchavez\AwsSesBounce\Services\AwsSesBounce;

if (! function_exists('awsSesBounce')) {
    /**
     * @return AwsSesBounce
     */
    function awsSesBounce(): AwsSesBounce
    {
        return resolve('aws-ses-bounce');
    }
}

if (! function_exists('aws_ses_bounce')) {
    /**
     * @return AwsSesBounce
     */
    function aws_ses_bounce(): AwsSesBounce
    {
        return awsSesBounce();
    }
}
