<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademyRequest;
use App\Http\Requests\UpdateAcademyRequest;
use App\Models\Academy;
use Illuminate\Http\JsonResponse;

class AcademyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $academies = Academy::all();

        if($academies == null){
            return response()->json(["message" => "No academies"], 404);
        }
        return response()->json($academies);
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
    public function show(Academy $academy) : JsonResponse
    {
        if($academy == null){
            return response()->json(["message" => "No Academy"], 404);
        }
        return response()->json($academy);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademyRequest $request, Academy $academy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Academy $academy)
    {
        //
    }
}
