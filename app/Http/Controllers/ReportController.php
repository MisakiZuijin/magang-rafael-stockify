<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\CategoriService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected CategoriService $categoryService
    ) {}

    /**
     * Laporan untuk Admin (existing)
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
            'filters'
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
            // user_id sengaja tidak ada karena manager tidak butuh filter per pengguna
        ];

        $stockReport = $this->reportService->getStockReport(
            $filters['stock_status'],
            $filters['category_id']
        );

        $transactionReport = $this->reportService->getTransactionReport(
            $filters['start_date'],
            $filters['end_date'],
            $filters['type'],
            $filters['category_id'],
            null // user_id selalu null untuk manager
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
            null // user_id selalu null untuk manager
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
            'filters'
        ));
    }
}
