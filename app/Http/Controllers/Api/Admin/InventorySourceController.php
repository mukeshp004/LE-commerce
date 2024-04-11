<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventorySourceRequest;
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
    public function store(InventorySourceRequest $request)
    {
        $inventorySource = new InventorySource();
        $validated = $request->validated();

        $data = $request->only($inventorySource->getFillable());

        $inventorySource = InventorySource::create($data);

        return response()->json($inventorySource, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(InventorySource $inventorySource)
    {
        //
        return $inventorySource;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventorySource $inventorySource)
    {
        $inventorySource->update($request->only($inventorySource->getFillable()));

        return response()->json($inventorySource, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventorySource $inventorySource)
    {
        if ($inventorySource->delete()) {
            return response()->json($inventorySource, Response::HTTP_NO_CONTENT);
        }
    }
}
