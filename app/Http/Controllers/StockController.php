<?php

namespace App\Http\Controllers;

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

        return redirect()->route('stock.index')
            ->with('success', 'Stock opname berhasil disimpan.');
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
}
