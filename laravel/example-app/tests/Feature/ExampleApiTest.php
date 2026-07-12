<?php

namespace Tests\Feature;

use App\Models\Example;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Concurrency;
use Tests\TestCase;

class ExampleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_examples(): void
    {
        Example::factory()->count(20)->create();

        $response = $this->getJson('/api/examples');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $this->assertCount(15, $response->json('data'));
    }

    public function test_show_returns_single_example(): void
    {
        $example = Example::factory()->create();

        $response = $this->getJson("/api/examples/{$example->id}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data',
            ]);
    }

    public function test_store_creates_example_and_clears_cache(): void
    {
        Cache::spy();

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

        $this->assertDatabaseHas('examples', $payload);

        Cache::shouldHaveReceived('forget')
            ->once()
            ->with('examples:all');

        Cache::shouldHaveReceived('tags')
            ->once();
    }

    public function test_update_modifies_example_and_clears_cache(): void
    {
        Cache::spy();

        $example = Example::factory()->create();

        $payload = [
            'name' => 'Updated Example',
            'note' => '123456'
        ];

        $response = $this->putJson("/api/examples/{$example->id}", $payload);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Example updated successfully',
            ]);

        $this->assertDatabaseHas('examples', [
            'id' => $example->id,
            'name' => 'Updated Example',
        ]);

        Cache::shouldHaveReceived('forget')
            ->once()
            ->with('examples:all');

        Cache::shouldHaveReceived('tags')
            ->once();
    }

    public function test_destroy_deletes_example_and_clears_cache(): void
    {
        Cache::spy();

        Concurrency::shouldReceive('run')
            ->once()
            ->andReturnUsing(function (array $tasks, int $timeout) {
                foreach ($tasks as $task) {
                    $task();
                }

                return [];
            });

        $example = Example::factory()->create();

        $response = $this->deleteJson("/api/examples/{$example->id}");

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Example deleted successfully',
            ]);

        $this->assertDatabaseMissing('examples', [
            'id' => $example->id,
        ]);

        Cache::shouldHaveReceived('forget')
            ->once()
            ->with('examples:all');

        Cache::shouldHaveReceived('tags')
            ->once();
    }

    public function test_custom_cached_returns_examples(): void
    {
        Example::factory()->count(3)->create();

        Cache::forget('examples:all');

        $response = $this->getJson('/api/examples/cached');

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Example cache retrieved successfully',
            ]);

        $this->assertTrue(Cache::has('examples:all'));
    }

    public function test_store_validates_request(): void
    {
        $response = $this->postJson('/api/examples', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'note'
        ]);
    }

    public function test_update_validates_request(): void
    {
        $example = Example::factory()->create();

        $response = $this->putJson("/api/examples/{$example->id}", []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'note'
        ]);
    }
}