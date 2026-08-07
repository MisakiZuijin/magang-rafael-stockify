<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\view\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(): View
    {
        $products = $this->productService->getAllProducts();

        return view('pages.dashboard.admin.admindashboard', compact('products'));
    }

    /**
     * Tampilkan detail 1 user
     */
    public function show(int $id): View
    {
        $product = $this->productService->getAllProducts($id);

        return view('pages.dashboard.admin.admindashboard', compact('product'));
    }
}
