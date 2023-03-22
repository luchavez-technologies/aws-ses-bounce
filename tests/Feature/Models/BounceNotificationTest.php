<?php

namespace Luchavez\AwsSesBounce\Feature\Models;

use Tests\TestCase;

/**
 * Class BounceNotificationTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class BounceNotificationTest extends TestCase
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
