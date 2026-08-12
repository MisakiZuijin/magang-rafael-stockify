<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\StockTransaction;
use App\Services\StockService;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct(
        protected StockService $stockService,
        protected ProductService $productService
    ) {}

    public function index(Request $request): View
    {
        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');
        $searchMin     = $request->input('search_min', '');

        $transactions = $this->stockService->getAllTransactions();
        $products     = $this->productService->getAllProducts();
        $recentActivities = $this->stockService->getRecentActivities(5);
        $lowStockProducts = $this->stockService->getLowStockProducts();

        $desc = $sortDirection === 'desc';

        // 1. SEARCH & SORT TRANSAKSI (tabel riwayat)
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
            'date'     => $transactions->sortBy('date', SORT_REGULAR, $desc),
            'product'  => $transactions->sortBy(fn($t) => $t->product?->name ?? '', SORT_REGULAR, $desc),
            'user'     => $transactions->sortBy(fn($t) => $t->user?->name ?? '', SORT_REGULAR, $desc),
            'type'     => $transactions->sortBy('type', SORT_REGULAR, $desc),
            'quantity' => $transactions->sortBy('quantity', SORT_REGULAR, $desc),
            'status'   => $transactions->sortBy('status', SORT_REGULAR, $desc),
            default    => $transactions->sortBy('id', SORT_REGULAR, $desc),
        };

        $productsSorted = $products;

        if ($searchMin) {
            $sm = strtolower($searchMin);
            $productsSorted = $productsSorted->filter(function ($p) use ($sm) {
                return str_contains(strtolower($p->name), $sm)
                    || str_contains(strtolower($p->sku ?? ''), $sm)
                    || str_contains(strtolower((string) $p->id), $sm);
            })->values();
        }

        // 2. SORT PRODUK (tabel minimum stock)
        $productsSorted = match ($sortColumn) {
            'product_name'  => $productsSorted->sortBy('name', SORT_REGULAR, $desc),
            'stock'         => $productsSorted->sortBy('stock', SORT_REGULAR, $desc),
            'minimum_stock' => $productsSorted->sortBy('minimum_stock', SORT_REGULAR, $desc),
            default         => $productsSorted->sortBy('id', SORT_REGULAR, $desc),
        };

        return view('pages.admin.adminstock', compact(
            'transactions',
            'products',
            'productsSorted',
            'recentActivities',
            'lowStockProducts',
            'sortColumn',
            'sortDirection',
            'search',
            'searchMin'
        ));
    }

    public function opname(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'   => ['required', 'integer', 'exists:products,id'],
            'actual_stock' => ['required', 'integer', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:255'],
        ]);

        $this->stockService->opname(
            $validated['product_id'],
            $validated['actual_stock'],
            $validated['notes'] ?? null
        );

        $redirectRoute = auth()->user()->role === 'Manager Gudang'
            ? 'manager.stock'
            : 'stock.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Stock opname berhasil disimpan.');

        // return redirect()->route('stock.index')
        //     ->with('success', 'Stock opname berhasil disimpan.');
    }

    public function managerOpname(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'   => ['required', 'integer', 'exists:products,id'],
            'actual_stock' => ['required', 'integer', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $oldStock = $product->stock;
        $newStock = $validated['actual_stock'];
        $difference = $newStock - $oldStock;

        // Update stok produk
        $product->update(['stock' => $newStock]);

        // Catat transaksi opname sebagai transaksi Masuk/Keluar tergantung selisih
        if ($difference !== 0) {
            StockTransaction::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => $difference > 0 ? 'Masuk' : 'Keluar',
                'quantity'   => abs($difference),
                'date'       => now(),
                'status'     => 'Diterima',
                'note'       => 'Stock Opname' . ($validated['notes'] ? ' - ' . $validated['notes'] : '') . ' | Fisik: ' . $newStock . ', Sistem: ' . $oldStock,
            ]);
        }

        return redirect()->route('manager.stock')->with('success', 'Stock opname berhasil disimpan.');
    }

    public function updateMinimum(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        $this->stockService->setMinimumStock($id, $validated['minimum_stock']);

        return redirect()->route('stock.index')
            ->with('success', 'Minimum stock berhasil diperbarui.');
    }

    public function manager(Request $request): View
    {
        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        $today = now()->toDateString();

        // Ambil semua transaksi
        $transactions = \App\Models\StockTransaction::with(['product', 'user'])->get();

        // Filter & Search Masuk
        $incomingTransactions = $transactions->filter(fn($t) => ($t->type ?? '') === 'Masuk');
        if ($search) {
            $s = strtolower($search);
            $incomingTransactions = $incomingTransactions->filter(
                fn($t) =>
                str_contains(strtolower($t->product?->name ?? ''), $s) ||
                    str_contains(strtolower($t->status ?? ''), $s) ||
                    str_contains(strtolower((string) $t->id), $s)
            )->values();
        }
        $incomingTransactions = $this->sortTransactions($incomingTransactions, $sortColumn, $sortDirection);

        // Filter & Search Keluar
        $outgoingTransactions = $transactions->filter(fn($t) => ($t->type ?? '') === 'Keluar');
        if ($search) {
            $s = strtolower($search);
            $outgoingTransactions = $outgoingTransactions->filter(
                fn($t) =>
                str_contains(strtolower($t->product?->name ?? ''), $s) ||
                    str_contains(strtolower($t->status ?? ''), $s) ||
                    str_contains(strtolower((string) $t->id), $s)
            )->values();
        }
        $outgoingTransactions = $this->sortTransactions($outgoingTransactions, $sortColumn, $sortDirection);

        $todayIncoming = $transactions->filter(fn($t) => ($t->type ?? '') === 'Masuk' && !empty($t->date) && \Carbon\Carbon::parse($t->date)->toDateString() === $today);
        $todayOutgoing = $transactions->filter(fn($t) => ($t->type ?? '') === 'Keluar' && !empty($t->date) && \Carbon\Carbon::parse($t->date)->toDateString() === $today);
        $pendingCount  = $transactions->where('status', 'Pending')->count();

        $products = \App\Models\Product::orderBy('name')->get();

        return view('pages.manager.managerstock', compact(
            'incomingTransactions',
            'outgoingTransactions',
            'todayIncoming',
            'todayOutgoing',
            'pendingCount',
            'products',
            'sortColumn',
            'sortDirection',
            'search'
        ));
    }

    private function sortTransactions($collection, string $column, string $direction)
    {
        $desc = $direction === 'desc';
        return match ($column) {
            'date'     => $collection->sortBy('date', SORT_REGULAR, $desc),
            'product'  => $collection->sortBy(fn($t) => $t->product?->name ?? '', SORT_REGULAR, $desc),
            'quantity' => $collection->sortBy('quantity', SORT_REGULAR, $desc),
            'status'   => $collection->sortBy('status', SORT_REGULAR, $desc),
            default    => $collection->sortBy('id', SORT_REGULAR, $desc),
        };
    }
}
