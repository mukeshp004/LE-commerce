<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
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
    public function store(Request $request)
    {
        //
        
        // $file = $request->file('image');
        // return $file->getClientOriginalName();
        
        // return $file->storeAs(
        //     'uploads/images',
        //     $file->getClientOriginalName(),
        //     'public'
        // );

        // return "no files";
        // return request()->all();
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
        $file = $request->file('image');
        // return $file->getClientOriginalName();
        // return "no files";
        return request()->all();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
