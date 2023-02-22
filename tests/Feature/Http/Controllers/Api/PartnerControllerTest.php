<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Partner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PartnerControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function model()
    {
        return Partner::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->partner = Partner::factory()->create();
    }

    public function test_listing_all_partners(): void
    {
        $response = $this->get('/api/v1/partners');

        $response->assertStatus(200);
    }
}
