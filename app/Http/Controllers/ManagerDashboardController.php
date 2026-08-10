<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // 1. STOK KRITIS
        $criticalProducts = $products->filter(function ($product) {
            $min = $product->minimum_stock ?? 0;
            return $product->stock <= $min || $product->stock == 0;
        });

        // 2. TRANSAKSI HARI INI
        $today = now()->toDateString();

        $todayIncoming = $transactions->filter(function ($trx) use ($today) {
            return ($trx->type ?? '') === 'Masuk'
                && !empty($trx->date)
                && Carbon::parse($trx->date)->toDateString() === $today;
        })->sortByDesc('date')->values();

        $todayOutgoing = $transactions->filter(function ($trx) use ($today) {
            return ($trx->type ?? '') === 'Keluar'
                && !empty($trx->date)
                && Carbon::parse($trx->date)->toDateString() === $today;
        })->sortByDesc('date')->values();

        return view('pages.manager.managerdashboard', compact(
            'products',
            'transactions',
            'recentActivities',
            'criticalProducts',
            'todayIncoming',
            'todayOutgoing'
        ));
    }

    public function criticalProducts(Request $request): View
    {
        $query = Product::with('categori')
            ->where(function ($q) {
                $q->whereColumn('stock', '<=', 'minimum_stock')
                    ->orWhere('stock', 0);
            });

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('categori', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $products = $query->orderBy('stock', 'asc')->paginate(25)->withQueryString();

        return view('pages.manager.manager-critical-products', compact('products'));
    }
}
