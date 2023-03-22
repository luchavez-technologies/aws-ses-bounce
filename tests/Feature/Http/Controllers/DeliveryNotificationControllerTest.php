<?php

namespace Luchavez\AwsSesBounce\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * Class DeliveryNotificationControllerTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeliveryNotificationControllerTest extends TestCase
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
