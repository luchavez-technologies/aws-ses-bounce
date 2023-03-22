<?php

namespace Luchavez\AwsSesBounce\Feature\Models;

use Tests\TestCase;

/**
 * Class DeliveryNotificationTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeliveryNotificationTest extends TestCase
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
