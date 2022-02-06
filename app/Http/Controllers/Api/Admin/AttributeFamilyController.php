<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeFamily;
use App\Models\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AttributeFamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AttributeFamily::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  AttributeFamily  $family
     * @return \Illuminate\Http\Response
     */
    public function show(AttributeFamily $family)
    {
        return $family;
        // $attributes = Attribute::all(['id', 'code', 'type']);
        // return [
        //     'family' => $family,
        //     'attributes' => $attributes
        // ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttributeFamily $family)
    {
        // return $request->all();
        $family->update($request->only($family->getFillable()));

        $groups = $request->get('groups', []);

        foreach ($groups as $key => $groupInput) {
            if (isset($groupInput['id'])) {
                $group = AttributeGroup::find($groupInput['id']);;
                $group->update(Arr::only($groupInput, $group->getFillable()));


                $attributeIds = $group->attributes()->get()->pluck('id');

                if (isset($groupInput['attributes'])) {
                    foreach ($groupInput['attributes'] as $key => $attribute) {
                        if (is_numeric($index = $attributeIds->search($attribute['id']))) {
                            $attributeIds->forget($index);
                        } else {
                            $attributeModel = Attribute::find($attribute['id']);
                            $group->attributes()->save($attributeModel, ['position' => $key + 1]);
                        }
                    }
                }

                // this will remove the remaining attributes
                if ($attributeIds->count()) {
                    $group->attributes()->detach($attributeIds);
                }
            } else {
                $group = $family->groups()->create($groupInput);

                foreach ($groupInput['attributes'] as $key => $attribute) {
                    $attribute =  Attribute::find($attribute['id']);

                    $group->attributes()->save($attribute, ['position' => $key + 1]);
                }
            }
        }
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
