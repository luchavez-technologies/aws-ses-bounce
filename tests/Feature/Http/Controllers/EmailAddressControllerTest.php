<?php

namespace Luchavez\AwsSesBounce\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * Class EmailAddressControllerTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmailAddressControllerTest extends TestCase
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
