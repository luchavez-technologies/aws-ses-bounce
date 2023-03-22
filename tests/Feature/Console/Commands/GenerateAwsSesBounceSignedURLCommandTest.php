<?php

namespace Luchavez\AwsSesBounce\Feature\Console\Commands;

use Tests\TestCase;

/**
 * Class GenerateAwsSesBounceSignedURLCommandTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class GenerateAwsSesBounceSignedURLCommandTest extends TestCase
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
