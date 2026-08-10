<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\view\View;
use Illuminate\Http\Request;

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

        return view('pages.admin.admindashboard', compact(
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

    public function fullActivities(Request $request): View
    {
        // Sesuaikan query ini dengan sumber data $recentActivities kamu
        // Contoh 1: Jika aktivitas = transaksi
        $query = \App\Models\StockTransaction::with(['user', 'product'])
            ->select('stock_transactions.*')
            ->selectRaw("'transaksi' as type")
            ->selectRaw("CONCAT('Melakukan transaksi ', type, ' pada produk ', COALESCE((SELECT name FROM products WHERE products.id = stock_transactions.product_id), 'Unknown')) as description");

        // Contoh 2: Jika kamu punya tabel activities terpisah
        // $query = \App\Models\Activity::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $activities = $query->orderByDesc('created_at')->paginate(25)->withQueryString();

        return view('pages.admin.adminactivity-full', compact('activities'));
    }
}
