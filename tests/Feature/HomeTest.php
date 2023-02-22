<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertSee('Send your Order!');
        $response->assertSeeText('Made with Laravel v10.0.2');
        $response->assertSeeText('PHP v8.2.3');
        $response->assertStatus(200);
    }
}
