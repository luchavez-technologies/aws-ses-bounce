<?php

namespace Luchavez\AwsSesBounce\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class EmptyToRecipientsException
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmptyToRecipientsException extends Exception
{
    /**
     * Constructor
     */
    public function __construct(public Collection|array $blocked_emails = [])
    {
        parent::__construct();
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return simpleResponse()
            ->data($this->blocked_emails)
            ->failed()
            ->message('Email cancelled due to empty `to` recipients.')
            ->generate();
    }
}
