<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\ExampleRequest;
use App\Http\Resources\ExampleResource;
use App\Http\Resources\ExampleScanResource;
use App\Models\Example;


class ExampleController extends Controller
{
    // GET http://localhost:8000/api/examples
    public function index(Request $request): ExampleScanResource
    {
        $examples = Example::paginate(15);
        return new ExampleScanResource($examples);
    }

    // POST
    public function store(ExampleRequest $request): JsonResponse
    {
        $example = Example::create($request->validated());

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

    // PUT/PATCH
    public function update(ExampleRequest $request, Example $example): JsonResponse
    {
        $example->update($request->validated());

        return response()->json([
            'message' => 'Example updated successfully',
            'data' => new ExampleResource($example),
        ]);
    }

    // DELETE http://localhost:8000/api/examples/5
    public function destroy(Example $example): JsonResponse
    {
        $example->delete();

        return response()->json([
            'message' => 'Example deleted successfully',
        ], 200);
    }
}
