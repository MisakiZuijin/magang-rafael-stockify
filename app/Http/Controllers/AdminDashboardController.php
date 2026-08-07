<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\view\View;

class AdminDashboardController extends Controller
{
    public function __construct(

        protected ProductService $productService,
        protected TransactionService $transactionService,
    ) {}

    public function index(): View
    {
        $products = $this->productService->getAllProducts();
        $transactions = $this->transactionService->getAllTransaction();
        $recentActivities = $this->transactionService->getRecentActivities(5);

        return view('pages.dashboard.admin.admindashboard', compact(
            'products',
            'transactions',
            'recentActivities'
        ));
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
