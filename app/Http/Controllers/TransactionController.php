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

        $backRoute = auth()->user()->role === 'Manager Gudang'
            ? route('manager.dashboard')
            : route('stock.index');

        return view('pages.show', [
            'title'       => 'Detail Transaksi',
            'subtitle'    => 'ID: #' . $transaction->id,
            'backRoute'   => $backRoute,
            'editRoute'   => null, // Transaksi biasanya tidak diedit
            'deleteRoute' => null,
            'fields'      => [
                ['label' => 'ID Transaksi', 'value' => '#' . $transaction->id],
                ['label' => 'Tanggal', 'value' => $transaction->date, 'type' => 'date'],
                ['label' => 'Produk', 'value' => $transaction->product?->name],
                ['label' => 'User/Pengguna', 'value' => $transaction->user?->name],
                ['label' => 'Tipe', 'value' => $transaction->type, 'type' => 'badge', 'class' => $transaction->type === 'Masuk' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'],
                ['label' => 'Jumlah', 'value' => $transaction->quantity],
                ['label' => 'Status', 'value' => $transaction->status, 'type' => 'badge', 'class' => match ($transaction->status) {
                    'Diterima' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                    'Pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                    'Dikeluarkan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                    default => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                }],
                ['label' => 'Catatan', 'value' => $transaction->note],
            ],
        ]);
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
