<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\CategoriService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected CategoriService $categoryService
    ) {}

    public function index(Request $request): View
    {
        $filters = [
            'start_date'   => $request->input('start_date', now()->startOfMonth()->toDateString()),
            'end_date'     => $request->input('end_date', now()->toDateString()),
            'category_id'  => $request->input('category_id'),
            'type'         => $request->input('type'),       // Masuk / Keluar
            'user_id'      => $request->input('user_id'),
            'stock_status' => $request->input('stock_status'), // aman / kritis / habis
        ];

        // Data Laporan
        $stockReport = $this->reportService->getStockReport(
            $filters['stock_status'],
            $filters['category_id']
        );

        $transactionReport = $this->reportService->getTransactionReport(
            $filters['start_date'],
            $filters['end_date'],
            $filters['type'],
            $filters['category_id'],
            $filters['user_id']
        );

        $userActivityReport = $this->reportService->getUserActivityReport(
            $filters['start_date'],
            $filters['end_date'],
            $filters['user_id']
        );

        // Summary
        $stockSummary = $this->reportService->getStockSummary();
        $transactionSummary = $this->reportService->getTransactionSummary(
            $filters['start_date'],
            $filters['end_date']
        );

        // Chart Data
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

        // Filter Dropdowns
        $categories = $this->categoryService->getAllCategories();
        $users = User::select('id', 'name', 'role')->orderBy('name')->get();

        return view('pages.admin.adminlaporan', compact(
            'stockReport',
            'transactionReport',
            'userActivityReport',
            'stockSummary',
            'transactionSummary',
            'stockChart',
            'transactionChart',
            'categories',
            'users',
            'filters'
        ));
    }
}
