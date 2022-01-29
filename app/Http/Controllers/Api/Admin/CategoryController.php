<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories =  Category::all();
        // return CategoryResource::collection($categories);
        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = new Category();
        $validated = $request->validated();

        $data = $request->only($category->getFillable());
        $data['uuid'] = Str::uuid();

        // return $data;
        $category = Category::create($data);

        // return new CategoryResource($category);
        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
        // return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->only($category->getFillable()));

        // return new CategoryResource($category);
        return response()->json($category, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        // return response()->json($category, Response::HTTP_NO_CONTENT);
        if ($category->delete()) {
            // return new CategoryResource($category);
            return response()->json($category, Response::HTTP_NO_CONTENT);
        }
    }
}
