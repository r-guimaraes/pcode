<?php

namespace Tests\Feature\Http\Controllers\Api;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function model()
    {
        return Order::class;
    }

    public function test_listing_all_orders(): void
    {
        $response = $this->getJson('api/v1/orders');
        $response->assertStatus(200);
    }

}
