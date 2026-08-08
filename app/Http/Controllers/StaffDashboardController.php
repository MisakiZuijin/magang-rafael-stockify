<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected TransactionService $transactionService,
    ) {}

    public function index(): View
    {
        $products     = $this->productService->getAllProducts();
        $transactions = $this->transactionService->getAllTransaction();

        return view('pages.staff.userdashboard', compact(
            'products',
            'transactions'
        ));
    }
}
