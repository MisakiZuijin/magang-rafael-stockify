<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\CategoriService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected CategoriService $categoryService
    ) {}

    /**
     * Laporan untuk Admin
     */
    public function index(Request $request): View
    {
        $filters = [
            'start_date'   => $request->input('start_date', now()->startOfMonth()->toDateString()),
            'end_date'     => $request->input('end_date', now()->toDateString()),
            'category_id'  => $request->input('category_id'),
            'type'         => $request->input('type'),
            'user_id'      => $request->input('user_id'),
            'stock_status' => $request->input('stock_status'),
        ];

        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        // === DATA STOK: Search + Sort ===
        $stockReport = $this->reportService->getStockReport(
            $filters['stock_status'],
            $filters['category_id']
        );

        $stockReport = $this->applySearch($stockReport, $search, 'stock');
        $stockReport = $this->applySort($stockReport, $sortColumn, $sortDirection, 'stock');

        // === DATA TRANSAKSI: Search + Sort ===
        $transactionReport = $this->reportService->getTransactionReport(
            $filters['start_date'],
            $filters['end_date'],
            $filters['type'],
            $filters['category_id'],
            $filters['user_id']
        );

        $transactionReport = $this->applySearch($transactionReport, $search, 'transaction');
        $transactionReport = $this->applySort($transactionReport, $sortColumn, $sortDirection, 'transaction');

        // Data lainnya
        $userActivityReport = $this->reportService->getUserActivityReport(
            $filters['start_date'],
            $filters['end_date'],
            $filters['user_id']
        );

        $stockSummary = $this->reportService->getStockSummary();
        $transactionSummary = $this->reportService->getTransactionSummary(
            $filters['start_date'],
            $filters['end_date']
        );

        $stockChart = $this->reportService->getStockChartData(
            $filters['stock_status'],
            $filters['category_id']
        );

        $transactionChart = $this->reportService->getTransactionChartData(
            $filters['start_date'],
            $filters['end_date'],
            $filters['type'],
            $filters['category_id'],
            $filters['user_id']
        );

        $categories = $this->categoryService->getAllCategories();

        return view('pages.admin.adminlaporan', compact(
            'stockReport',
            'transactionReport',
            'userActivityReport',
            'stockSummary',
            'transactionSummary',
            'stockChart',
            'transactionChart',
            'categories',
            'filters',
            'sortColumn',
            'sortDirection',
            'search'
        ));
    }

    /**
     * Laporan untuk Manager Gudang (TANPA aktivitas pengguna)
     */
    public function managerIndex(Request $request): View
    {
        $filters = [
            'start_date'   => $request->input('start_date', now()->startOfMonth()->toDateString()),
            'end_date'     => $request->input('end_date', now()->toDateString()),
            'category_id'  => $request->input('category_id'),
            'type'         => $request->input('type'),
            'stock_status' => $request->input('stock_status'),
        ];

        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        // === DATA STOK: Search + Sort ===
        $stockReport = $this->reportService->getStockReport(
            $filters['stock_status'],
            $filters['category_id']
        );

        $stockReport = $this->applySearch($stockReport, $search, 'stock');
        $stockReport = $this->applySort($stockReport, $sortColumn, $sortDirection, 'stock');

        // === DATA TRANSAKSI: Search + Sort ===
        $transactionReport = $this->reportService->getTransactionReport(
            $filters['start_date'],
            $filters['end_date'],
            $filters['type'],
            $filters['category_id'],
            null
        );

        $transactionReport = $this->applySearch($transactionReport, $search, 'transaction');
        $transactionReport = $this->applySort($transactionReport, $sortColumn, $sortDirection, 'transaction');

        $stockSummary = $this->reportService->getStockSummary();
        $transactionSummary = $this->reportService->getTransactionSummary(
            $filters['start_date'],
            $filters['end_date']
        );

        $stockChart = $this->reportService->getStockChartData(
            $filters['stock_status'],
            $filters['category_id']
        );

        $transactionChart = $this->reportService->getTransactionChartData(
            $filters['start_date'],
            $filters['end_date'],
            $filters['type'],
            $filters['category_id'],
            null
        );

        $categories = $this->categoryService->getAllCategories();

        return view('pages.manager.managerlaporan', compact(
            'stockReport',
            'transactionReport',
            'stockSummary',
            'transactionSummary',
            'stockChart',
            'transactionChart',
            'categories',
            'filters',
            'sortColumn',
            'sortDirection',
            'search'
        ));
    }

    /**
     * Filter collection berdasarkan keyword search
     */
    private function applySearch(Collection $collection, string $search, string $type): Collection
    {
        if (empty($search)) {
            return $collection;
        }

        $s = strtolower($search);

        return $collection->filter(function ($item) use ($s, $type) {
            if ($type === 'stock') {
                /** @var \App\Models\Product $item */
                return str_contains(strtolower($item->name), $s)
                    || str_contains(strtolower($item->sku ?? ''), $s)
                    || str_contains(strtolower($item->categori?->name ?? ''), $s)
                    || str_contains(strtolower($item->supplier?->name ?? ''), $s)
                    || str_contains(strtolower((string) $item->id), $s);
            }

            // type === 'transaction'
            /** @var \App\Models\StockTransaction $item */
            return str_contains(strtolower($item->product?->name ?? ''), $s)
                || str_contains(strtolower($item->user?->name ?? ''), $s)
                || str_contains(strtolower($item->status ?? ''), $s)
                || str_contains(strtolower($item->note ?? ''), $s)
                || str_contains(strtolower((string) $item->id), $s)
                || str_contains(strtolower($item->type ?? ''), $s);
        })->values();
    }

    /**
     * Sort collection berdasarkan kolom dan arah
     */
    private function applySort(Collection $collection, string $column, string $direction, string $type): Collection
    {
        $desc = $direction === 'desc';

        if ($type === 'stock') {
            return match ($column) {
                'category' => $collection->sortBy(fn($p) => $p->categori?->name ?? '', SORT_REGULAR, $desc),
                'supplier' => $collection->sortBy(fn($p) => $p->supplier?->name ?? '', SORT_REGULAR, $desc),
                'name', 'sku', 'purchase_price', 'selling_price', 'stock', 'minimum_stock', 'id'
                => $collection->sortBy($column, SORT_REGULAR, $desc),
                default => $collection->sortBy('id', SORT_REGULAR, $desc),
            };
        }

        // type === 'transaction'
        return match ($column) {
            'product' => $collection->sortBy(fn($t) => $t->product?->name ?? '', SORT_REGULAR, $desc),
            'user'    => $collection->sortBy(fn($t) => $t->user?->name ?? '', SORT_REGULAR, $desc),
            'date', 'quantity', 'status', 'type', 'id'
            => $collection->sortBy($column, SORT_REGULAR, $desc),
            default => $collection->sortBy('id', SORT_REGULAR, $desc),
        };
    }
}
