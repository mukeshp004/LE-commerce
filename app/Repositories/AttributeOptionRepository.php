<?php

namespace App\Repositories;

use App\Models\AttributeOption;

class AttributeOptionRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return AttributeOption::class;
    }

    /**
     * @param  array  $data
     * @return  App\Models\AttributeOption
     */
    public function create(array $data)
    {
        $option = parent::create($data);

        $this->uploadSwatchImage($data, $option->id);

        return $option;
    }

    /**
     * @param  array   $data
     * @param  int     $id
     * @param  string  $attribute
     * @return  App\Models\AttributeOptionOption
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $option = parent::update($data, $id);

        $this->uploadSwatchImage($data, $id);

        return $option;
    }

    /**
     * @param  array  $data
     * @param  int  $optionId
     * @return void
     */
    public function uploadSwatchImage($data, $optionId)
    {
        if (
            !isset($data['swatch_value'])
            || !$data['swatch_value']
        ) {
            return;
        }

        if ($data['swatch_value'] instanceof \Illuminate\Http\UploadedFile) {
            parent::update([
                'swatch_value' => $data['swatch_value']->store('attribute_option'),
            ], $optionId);
        }
    }
}
