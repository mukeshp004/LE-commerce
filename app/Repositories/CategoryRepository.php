<?php

namespace App\Repositories;

use App\Models\Category;

/**Repository
 * Class ProductRepository.
 *
 * @package namespace App\Repositories;
 */
class CategoryRepository extends  Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    /**
     * Retrieve category from slug.
     *
     * @param  string  $slug
     * @return \Webkul\Category\Contracts\Category
     */
    public function findBySlug($slug)
    {
        if ($category = $this->model->whereTranslation('slug', $slug)->first()) {
            return $category;
        }
    }
}
