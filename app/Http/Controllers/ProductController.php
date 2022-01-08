<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attribute = app('rinvex.attributes.attribute')->get();



        // app('rinvex.attributes.attribute')->create([
        //     'slug' => 'size',
        //     'type' => 'varchar',
        //     'name' => 'Product Size',
        //     'entities' => ['App\Models\Product'],
        // ]);
        return $product = Product::paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $product = new Product();
        $product->sku = "001";
        $product->type = "default";
        $product->size = "xl";
        $product->save();

        return $product;
        // $attribute = app('rinvex.attributes.attribute')->find(1);

        // return $entity = $attribute->entities();

        // // Get attribute entities collection
        // $entity = $attribute->entities()->createMany([
        //     ['content' => 'Xl', 'attribute_id' => 1, 'entity_type' => 'App\Models\Product', 'entity_id' => 1]
        // ]);


        //        dd($product);
        // return response()->json($product);


        //        $product = Product::create([
        //            [ "sku" => '001', "type" => "default"]
        //        ]);

        // $product->size = "xl";
        // $product->save();

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
