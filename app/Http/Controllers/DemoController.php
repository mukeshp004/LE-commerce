<?php

namespace App\Http\Controllers;


use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;


class DemoController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(

        protected CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    //
    public function index()
    {
        $slug = "maggie";

        $product = $this->productRepository->findBySlug($slug);

        dd($product->toArray());
    }
}
