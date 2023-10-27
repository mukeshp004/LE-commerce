<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventorySource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventorySourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $inventorySources =  InventorySource::all();
        // return CategoryResource::collection($categories);
        return response()->json($inventorySources, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
