<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function index(): View
    {
        $transactions = $this->transactionService->getAllTransaction();
        return view('pages.admin.admindashboard', compact('transactions'));
    }

    public function show(int $id): View
    {
        $transaction = $this->transactionService->getTransactionById($id);
        return view('pages.admin.admindashboard', compact('transaction'));
    }
}
