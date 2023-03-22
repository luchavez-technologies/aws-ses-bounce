<?php

namespace Luchavez\AwsSesBounce\Feature\Listeners;

use Tests\TestCase;

/**
 * Class ValidateEmailAddressListenerTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ValidateEmailAddressListenerTest extends TestCase
{
    /**
     * Example Test
     *
     * @test
     */
    public function example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
