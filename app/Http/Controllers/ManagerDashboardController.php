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

    public function index(Request $request): View
    {
        $products         = $this->productService->getAllProducts();
        $transactions     = $this->transactionService->getAllTransaction();
        $recentActivities = $this->transactionService->getRecentActivities(5);

        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        // 1. STOK KRITIS + Search + Sort
        $criticalProducts = $products->filter(function ($product) {
            $min = $product->minimum_stock ?? 0;
            return $product->stock <= $min || $product->stock == 0;
        });

        if ($search) {
            $s = strtolower($search);
            $criticalProducts = $criticalProducts->filter(
                fn($p) =>
                str_contains(strtolower($p->name), $s) ||
                    str_contains(strtolower($p->sku ?? ''), $s) ||
                    str_contains(strtolower($p->categori?->name ?? ''), $s) ||
                    str_contains(strtolower((string) $p->id), $s)
            )->values();
        }

        $desc = $sortDirection === 'desc';
        $criticalProducts = match ($sortColumn) {
            'name'          => $criticalProducts->sortBy('name', SORT_REGULAR, $desc),
            'category'      => $criticalProducts->sortBy(fn($p) => $p->categori?->name ?? '', SORT_REGULAR, $desc),
            'stock'         => $criticalProducts->sortBy('stock', SORT_REGULAR, $desc),
            'minimum_stock' => $criticalProducts->sortBy('minimum_stock', SORT_REGULAR, $desc),
            default         => $criticalProducts->sortBy('id', SORT_REGULAR, $desc),
        };

        // 2. TRANSAKSI HARI INI + Sort
        $today = now()->toDateString();

        $todayIncoming = $transactions->filter(function ($trx) use ($today) {
            return ($trx->type ?? '') === 'Masuk'
                && !empty($trx->date)
                && Carbon::parse($trx->date)->toDateString() === $today;
        })->values();

        $todayOutgoing = $transactions->filter(function ($trx) use ($today) {
            return ($trx->type ?? '') === 'Keluar'
                && !empty($trx->date)
                && Carbon::parse($trx->date)->toDateString() === $today;
        })->values();

        // Sort incoming/outgoing
        $todayIncoming = $this->sortTransactions($todayIncoming, $sortColumn, $sortDirection);
        $todayOutgoing = $this->sortTransactions($todayOutgoing, $sortColumn, $sortDirection);

        return view('pages.manager.managerdashboard', compact(
            'products',
            'transactions',
            'recentActivities',
            'criticalProducts',
            'todayIncoming',
            'todayOutgoing',
            'sortColumn',
            'sortDirection',
            'search'
        ));
    }

    public function criticalProducts(Request $request): View
    {
        $query = Product::with('categori')
            ->where(function ($q) {
                $q->whereColumn('stock', '<=', 'minimum_stock')
                    ->orWhere('stock', 0);
            });

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

        return view('pages.manager.fullview.manager-critical-products', compact('products'));
    }

    /**
     * Helper sort transaksi
     */
    private function sortTransactions($collection, string $column, string $direction)
    {
        $desc = $direction === 'desc';
        return match ($column) {
            'product'  => $collection->sortBy(fn($t) => $t->product?->name ?? '', SORT_REGULAR, $desc),
            'user'     => $collection->sortBy(fn($t) => $t->user?->name ?? '', SORT_REGULAR, $desc),
            'quantity' => $collection->sortBy('quantity', SORT_REGULAR, $desc),
            'status'   => $collection->sortBy('status', SORT_REGULAR, $desc),
            'id'       => $collection->sortBy('id', SORT_REGULAR, $desc),
            default    => $collection->sortBy('id', SORT_REGULAR, $desc),
        };
    }
}
