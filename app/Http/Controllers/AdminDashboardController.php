<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected TransactionService $transactionService,
    ) {}

    public function index(Request $request): View
    {
        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        $products         = $this->productService->getAllProducts();
        $transactions     = $this->transactionService->getAllTransaction();
        $recentActivities = $this->transactionService->getRecentActivities(5);

        // === SEARCH & SORT PRODUK ===
        if ($search) {
            $s = strtolower($search);
            $products = $products->filter(function ($p) use ($s) {
                return str_contains(strtolower($p->name), $s)
                    || str_contains(strtolower($p->sku ?? ''), $s)
                    || str_contains(strtolower($p->categori?->name ?? ''), $s);
            })->values();
        }

        $desc = $sortDirection === 'desc';
        $products = match ($sortColumn) {
            'name'           => $products->sortBy('name', SORT_REGULAR, $desc),
            'purchase_price' => $products->sortBy('purchase_price', SORT_REGULAR, $desc),
            'selling_price'  => $products->sortBy('selling_price', SORT_REGULAR, $desc),
            'stock'          => $products->sortBy('stock', SORT_REGULAR, $desc),
            'minimum_stock'  => $products->sortBy('minimum_stock', SORT_REGULAR, $desc),
            default          => $products->sortBy('id', SORT_REGULAR, $desc),
        };

        // === SEARCH & SORT TRANSAKSI ===
        if ($search) {
            $s = strtolower($search);
            $transactions = $transactions->filter(function ($t) use ($s) {
                return str_contains(strtolower($t->product?->name ?? ''), $s)
                    || str_contains(strtolower($t->user?->name ?? ''), $s)
                    || str_contains(strtolower($t->status ?? ''), $s)
                    || str_contains(strtolower($t->type ?? ''), $s)
                    || str_contains(strtolower((string) $t->id), $s);
            })->values();
        }

        $transactions = match ($sortColumn) {
            'product'  => $transactions->sortBy(fn($t) => $t->product?->name ?? '', SORT_REGULAR, $desc),
            'user'     => $transactions->sortBy(fn($t) => $t->user?->name ?? '', SORT_REGULAR, $desc),
            'quantity' => $transactions->sortBy('quantity', SORT_REGULAR, $desc),
            'status'   => $transactions->sortBy('status', SORT_REGULAR, $desc),
            'type'     => $transactions->sortBy('type', SORT_REGULAR, $desc),
            default    => $transactions->sortBy('id', SORT_REGULAR, $desc),
        };

        return view('pages.admin.admindashboard', compact(
            'products',
            'transactions',
            'recentActivities',
            'sortColumn',
            'sortDirection',
            'search'
        ));
    }

    public function fullActivities(Request $request): View
    {
        $query = \App\Models\StockTransaction::with(['user', 'product'])
            ->select('stock_transactions.*')
            ->selectRaw("'transaksi' as type")
            ->selectRaw("CONCAT('Melakukan transaksi ', type, ' pada produk ', COALESCE((SELECT name FROM products WHERE products.id = stock_transactions.product_id), 'Unknown')) as description");

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

        return view('pages.admin.fullview.adminactivity-full', compact('activities'));
    }
}
