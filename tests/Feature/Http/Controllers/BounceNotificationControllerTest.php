<?php

namespace Luchavez\AwsSesBounce\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * Class BounceNotificationControllerTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class BounceNotificationControllerTest extends TestCase
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
