<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademyRequest;
use App\Http\Requests\UpdateAcademyRequest;
use App\Models\Academy;

class AcademyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Academy $academy)
    {
        //
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
