<?php

namespace Luchavez\AwsSesBounce\Feature\Models;

use Tests\TestCase;

/**
 * Class ComplaintNotificationTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ComplaintNotificationTest extends TestCase
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
