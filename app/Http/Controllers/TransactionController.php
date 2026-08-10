<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
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

    public function full(Request $request): View
    {
        $query = StockTransaction::with(['product', 'user']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('product', function ($pq) use ($request) {
                    $pq->where('name', 'like', '%' . $request->search . '%');
                })
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->orderByDesc('date')->paginate(25)->withQueryString();

        return view('pages.admin.admintransaction-full', compact('transactions'));
    }
}
