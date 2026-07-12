<?php

namespace Tests\Feature;

use App\Models\Example;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ExampleCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_cached_returns_updated_examples(): void
    {
        $examples = Example::factory()->count(3)->create();
        Cache::forget('examples:all');

        // Test initial GET response
        $response = $this->getJson('/api/examples/cached');
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Example cache retrieved successfully',
            ])
            ->assertJsonCount(3, 'data');
        $this->assertTrue(Cache::has('examples:all'));

        // POST
        $payload = [
            'name' => 'Test Example',
            'note' => '123456'
        ];
        $response = $this->postJson('/api/examples', $payload);
        $response
            ->assertCreated()
            ->assertJson([
                'message' => 'Example created successfully',
            ]);
        $this->assertFalse(Cache::has('examples:all'));

        // Test GET cache after POST
        $response = $this->getJson('/api/examples/cached');
        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJson([
                'message' => 'Example cache retrieved successfully',
            ]);
        $this->assertTrue(Cache::has('examples:all'));

        // POST
        $payload = [
            'name' => 'Test Example',
            'note' => '123456'
        ];
        $response = $this->postJson('/api/examples', $payload);
        $response
            ->assertCreated()
            ->assertJson([
                'message' => 'Example created successfully',
            ]);
        $this->assertFalse(Cache::has('examples:all'));

        // Test GET cache after POST
        $response = $this->getJson('/api/examples/cached');
        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJson([
                'message' => 'Example cache retrieved successfully',
            ]);
        $this->assertTrue(Cache::has('examples:all'));
    }
}