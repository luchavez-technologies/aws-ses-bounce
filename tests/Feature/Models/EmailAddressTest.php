<?php

namespace Luchavez\AwsSesBounce\Feature\Models;

use Tests\TestCase;

/**
 * Class EmailAddressTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmailAddressTest extends TestCase
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
