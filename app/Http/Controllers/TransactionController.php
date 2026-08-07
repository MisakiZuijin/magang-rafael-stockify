<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\view\View;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function index(): View
    {
        $transactions = $this->transactionService->getAllTransaction();

        return view('pages.dashboard.admin.admindashboard', compact('transactions', 'product'));
    }

    /**
     * Tampilkan detail 1 user
     */
    public function show(int $id): View
    {
        $transaction = $this->transactionService->getAllTransaction($id);

        return view('pages.dashboard.admin.admindashboard', compact('transaction'));
    }
}
