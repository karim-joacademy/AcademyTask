<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademyRequest;
use App\Http\Requests\UpdateAcademyRequest;
use App\Models\Academy;
use Exception;
use Illuminate\Http\JsonResponse;

class AcademyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $academies = Academy::all();

            if ($academies->isEmpty()) {
                return response()->json(["message" => "No academies found"], 404);
            }
            return response()->json($academies, 200);
        }
        catch (Exception $e) {
            return response()->json(["message" => "An error occurred while retrieving academies", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcademyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Academy $academy): JsonResponse
    {
        try {
            if ($academy === null) {
                return response()->json(["message" => "No Academy found"], 404);
            }
            return response()->json($academy, 200);
        }
        catch (Exception $e) {
            return response()->json(["message" => "An error occurred while retrieving the academy", "error" => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademyRequest $request, int $id) : JsonResponse
    {
        try {
            $academy = Academy::query()->findOrFail($id); // Find academy by ID

            $academy->update([
                'name' => $request->input('name', $academy->name),
                'email' => $request->input('email', $academy->email),
                'phone' => $request->input('phone', $academy->phone),
            ]);

            return response()->json($academy, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update the academy.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id) : JsonResponse
    {
        try {
            $academy = Academy::query()->find($id);

            if (!$academy) {
                return response()->json(["message" => "Academy not found"], 404);
            }
            $academy->delete();

            return response()->json(['message' => 'Academy and all its related records are deleted successfully.'], 200);
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
