<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    // ==========================================
    // LAPORAN STOK BARANG
    // ==========================================
    public function getStockReport(?string $stockStatus = null, ?int $categoryId = null): Collection
    {
        $query = Product::with(['categori', 'supplier']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($stockStatus) {
            $query->where(function ($q) use ($stockStatus) {
                match ($stockStatus) {
                    'habis' => $q->where('stock', 0),
                    'kritis' => $q->whereColumn('stock', '<=', 'minimum_stock')->where('stock', '>', 0),
                    'aman' => $q->whereColumn('stock', '>', 'minimum_stock'),
                    default => $q,
                };
            });
        }

        return $query->orderBy('name')->get();
    }

    public function getStockSummary(): array
    {
        return [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('stock'),
            'stock_aman' => Product::whereColumn('stock', '>', 'minimum_stock')->count(),
            'stock_kritis' => Product::whereColumn('stock', '<=', 'minimum_stock')->where('stock', '>', 0)->count(),
            'stock_habis' => Product::where('stock', 0)->count(),
        ];
    }

    public function getStockChartData(?string $stockStatus = null, ?int $categoryId = null): array
    {
        $products = $this->getStockReport($stockStatus, $categoryId);

        $byCategory = $products->groupBy(fn($p) => $p->categori?->name ?? 'Tanpa Kategori')
            ->map(fn($items) => [
                'stock' => $items->sum('stock'),
                'minimum' => $items->sum('minimum_stock'),
                'count' => $items->count(),
            ]);

        return [
            'labels' => $byCategory->keys()->toArray(),
            'stock' => $byCategory->pluck('stock')->toArray(),
            'minimum' => $byCategory->pluck('minimum')->toArray(),
        ];
    }

    // ==========================================
    // LAPORAN TRANSAKSI (MASUK & KELUAR)
    // ==========================================
    public function getTransactionReport(string $startDate, string $endDate, ?string $type = null, ?int $categoryId = null, ?int $userId = null): Collection
    {
        $query = StockTransaction::with(['product.categori', 'user'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($type) {
            $query->where('type', $type);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($categoryId) {
            $query->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        return $query->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
    }

    public function getTransactionSummary(string $startDate, string $endDate): array
    {
        return [
            'total_masuk' => (int) StockTransaction::whereBetween('date', [$startDate, $endDate])->where('type', 'Masuk')->sum('quantity'),
            'total_keluar' => (int) StockTransaction::whereBetween('date', [$startDate, $endDate])->where('type', 'Keluar')->sum('quantity'),
            'count_masuk' => StockTransaction::whereBetween('date', [$startDate, $endDate])->where('type', 'Masuk')->count(),
            'count_keluar' => StockTransaction::whereBetween('date', [$startDate, $endDate])->where('type', 'Keluar')->count(),
        ];
    }

    public function getTransactionChartData(string $startDate, string $endDate, ?string $type = null, ?int $categoryId = null, ?int $userId = null): array
    {
        $transactions = $this->getTransactionReport($startDate, $endDate, $type, $categoryId, $userId);

        $dateRange = [];
        $current = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        while ($current <= $end) {
            $dateRange[$current->format('Y-m-d')] = ['masuk' => 0, 'keluar' => 0];
            $current->addDay();
        }

        // FIX: Pastikan date tidak null sebelum format
        $grouped = $transactions
            ->whereNotNull('date')
            ->groupBy(fn($t) => \Carbon\Carbon::parse($t->date)->format('Y-m-d'));

        foreach ($grouped as $date => $items) {
            if (isset($dateRange[$date])) {
                $dateRange[$date]['masuk'] = $items->where('type', 'Masuk')->sum('quantity');
                $dateRange[$date]['keluar'] = $items->where('type', 'Keluar')->sum('quantity');
            }
        }

        return [
            'labels' => array_keys($dateRange),
            'masuk' => array_column($dateRange, 'masuk'),
            'keluar' => array_column($dateRange, 'keluar'),
        ];
    }

    // ==========================================
    // LAPORAN AKTIVITAS PENGGUNA
    // ==========================================
    public function getUserActivityReport(string $startDate, string $endDate, ?int $userId = null): Collection
    {
        $query = User::withCount(['stockTransactions' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        }])
            ->with(['stockTransactions' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                    ->with('product')
                    ->orderBy('date', 'desc');
            }]);

        if ($userId) {
            $query->where('id', $userId);
        }

        return $query->get();
    }
}
