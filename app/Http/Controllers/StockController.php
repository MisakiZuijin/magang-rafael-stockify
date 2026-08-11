<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\StockTransaction;
use App\Services\StockService;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class StockController extends Controller
{
    public function __construct(
        protected StockService $stockService,
        protected ProductService $productService
    ) {}

    public function index(): View
    {
        $transactions = $this->stockService->getAllTransactions();
        $products = $this->productService->getAllProducts();
        $recentActivities = $this->stockService->getRecentActivities(5);
        $lowStockProducts = $this->stockService->getLowStockProducts();

        // Tidak perlu attach manual lagi — $product->minimum_stock langsung dari DB

        return view('pages.admin.adminstock', compact(
            'transactions',
            'products',
            'recentActivities',
            'lowStockProducts'
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

    public function manager(): View
    {
        $transactions = $this->stockService->getAllTransactions();
        $products     = $this->productService->getAllProducts();
        $today        = now()->toDateString();

        // Transaksi Masuk (50 terbaru)
        $incomingTransactions = $transactions
            ->where('type', 'Masuk')
            ->sortByDesc('date')
            ->values();

        // Transaksi Keluar (50 terbaru)
        $outgoingTransactions = $transactions
            ->where('type', 'Keluar')
            ->sortByDesc('date')
            ->values();

        // Stat hari ini
        $todayIncoming = $incomingTransactions->filter(
            fn($t) => !empty($t->date) && Carbon::parse($t->date)->toDateString() === $today
        );

        $todayOutgoing = $outgoingTransactions->filter(
            fn($t) => !empty($t->date) && Carbon::parse($t->date)->toDateString() === $today
        );

        $pendingCount = $transactions->where('status', 'Pending')->count();

        return view('pages.manager.managerstock', compact(
            'incomingTransactions',
            'outgoingTransactions',
            'todayIncoming',
            'todayOutgoing',
            'pendingCount',
            'products'
        ));
    }
}
