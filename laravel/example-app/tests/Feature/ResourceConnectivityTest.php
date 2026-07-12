<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ResourceConnectivityTest extends TestCase
{
    public function test_redis_connection(): void
    {
        $response = Redis::connection()->ping();

        $this->assertTrue(
            $response === true || strtoupper((string) $response) === 'PONG'
        );
    }

    public function test_database_can_be_connected_to(): void
    {
        $pdo = DB::connection()->getPdo();

        $this->assertNotNull($pdo);
        $this->assertTrue(DB::connection()->getDatabaseName() !== '');
    }
}