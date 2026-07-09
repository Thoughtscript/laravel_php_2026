<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\ExampleRequest;
use App\Http\Resources\ExampleResource;
use App\Http\Resources\ExampleScanResource;
use App\Models\Example;


class ExampleController extends Controller
{
    private function clear_cache() {
        Cache::forget('examples:all');
        Cache::tags(['examples'])->flush();
    }

    public function custom_cached(Request $request): JsonResponse
    {
        $examples =  Cache::remember('examples:all', 3600, function () { 
            return Example::all();
        });
        
        return response()->json([
            'message' => 'Example cache retreived successfully',
            'data' => $examples,
        ], 201);
    }

    // GET http://localhost:8000/api/examples
    public function index(Request $request): ExampleScanResource
    {
        $examples = Example::paginate(15);
        
        return new ExampleScanResource($examples);
    }

    // POST http://localhost:8000/api/examples
    public function store(ExampleRequest $request): JsonResponse
    {
        $example = Example::create($request->validated());
    
        $this->clear_cache();

        return response()->json([
            'message' => 'Example created successfully',
            'data' => new ExampleResource($example),
        ], 201);
    }

    // GET http://localhost:8000/api/examples/5
    public function show(Example $example): JsonResponse
    {
        return response()->json([
            'data' => new ExampleResource($example),
        ]);
    }

    // PUT/PATCH http://localhost:8000/api/examples/5
    public function update(ExampleRequest $request, Example $example): JsonResponse
    {
        $example->update($request->validated());

        $this->clear_cache();

        return response()->json([
            'message' => 'Example updated successfully',
            'data' => new ExampleResource($example),
        ]);
    }

    // DELETE http://localhost:8000/api/examples/5
    public function destroy(Example $example): JsonResponse
    {
        Concurrency::run([
            fn () => $example->delete(),
        ], timeout: 15);

        $this->clear_cache();

        return response()->json([
            'message' => 'Example deleted successfully',
        ], 200);
    }
}
