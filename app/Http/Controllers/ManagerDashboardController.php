<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\View\View;

class ManagerDashboardController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected TransactionService $transactionService,
    ) {}

    public function index(): View
    {
        $products         = $this->productService->getAllProducts();
        $transactions     = $this->transactionService->getAllTransaction();
        $recentActivities = $this->transactionService->getRecentActivities(5);
        $lowStockProducts = $products->where('stock', '<', 10);

        return view('pages.manager.managerdashboard', compact(
            'products',
            'transactions',
            'recentActivities'
        ));
    }
}
