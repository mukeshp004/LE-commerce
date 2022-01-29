<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Models\AttributeOption;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::all();
        return response()->json($attributes, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAttributeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttributeRequest $request)
    {

        $attribute = new Attribute();

        $data = $request->only($attribute->getFillable());
        $data['uuid'] = Str::uuid();


        $options = $request->has('options') ? $request->get('options') : [];

        unset($data['options']);


        DB::beginTransaction();

        $attribute = Attribute::create($data);

        try {
            if (in_array($attribute->type, [
                Attribute::$type['select'],
                Attribute::$type['multiselect'],
                Attribute::$type['radio'],
                Attribute::$type['checkbox'],
            ]) && count($options)) {
                foreach ($options as $option) {
                    $attributeOption = new AttributeOption();

                    // $option['attribute_id'] = $attribute->id;
                    $option = Arr::only($option, $attributeOption->getFillable());

                    $attribute->options()->create($option);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {


            DB::rollBack();
        }

        return response()->json($attribute->load(['options']), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        return $attribute;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttributeRequest  $request
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {

        DB::beginTransaction();

        try {
            $attribute->update($request->only($attribute->getFillable()));

            $options = $request->has('options') ? $request->get('options') : [];

            if (in_array($attribute->type, [
                Attribute::$type['select'],
                Attribute::$type['multiselect'],
                Attribute::$type['radio'],
                Attribute::$type['checkbox'],
            ]) && count($options)) {
                foreach ($options as $option) {
                    if (isset($option['id'])) {
                        $attribute->options()->updateOrCreate(
                            ['id' => $option['id']],  // where clause
                            Arr::only($option, $attribute->options()->getModel()->getFillable())
                        );
                    } else {
                        $option = Arr::only($option, $attribute->options()->getModel()->getFillable());
                        $attribute->options()->create($option);
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return response()->json($attribute->load(['options']), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        if ($attribute->delete()) {
            return response()->json($attribute, Response::HTTP_NO_CONTENT);
        }
    }
}
