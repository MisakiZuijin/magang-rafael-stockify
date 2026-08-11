<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected TransactionService $transactionService,
    ) {}

    /**
     * Dashboard Staff Gudang — Daftar tugas pending
     */
    public function index(): View
    {
        // Data dasar (konsisten dengan Admin & Manager)
        $products     = $this->productService->getAllProducts();
        $transactions = $this->transactionService->getAllTransaction();

        // Barang masuk yang menunggu konfirmasi penerimaan
        $incomingPending = StockTransaction::with(['product', 'user'])
            ->where('type', 'Masuk')
            ->where('status', 'Pending')
            ->orderBy('date', 'asc')
            ->get();

        // Barang keluar yang menunggu konfirmasi pengeluaran
        $outgoingPending = StockTransaction::with(['product', 'user'])
            ->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->orderBy('date', 'asc')
            ->get();

        // Ringkasan tugas
        $taskSummary = [
            'incoming_count' => $incomingPending->count(),
            'outgoing_count' => $outgoingPending->count(),
            'total_tasks'    => $incomingPending->count() + $outgoingPending->count(),
        ];

        return view('pages.staff.staffdashboard', compact(
            'products',
            'transactions',
            'incomingPending',
            'outgoingPending',
            'taskSummary'
        ));
    }

    /**
     * Halaman Konfirmasi Stok (Staff Stock)
     */
    public function stock(): View
    {
        $incomingPending = StockTransaction::with(['product', 'user'])
            ->where('type', 'Masuk')
            ->where('status', 'Pending')
            ->orderBy('date', 'asc')
            ->get();

        $outgoingPending = StockTransaction::with(['product', 'user'])
            ->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->orderBy('date', 'asc')
            ->get();

        return view('pages.staff.staffstock', compact(
            'incomingPending',
            'outgoingPending'
        ));
    }

    /**
     * Konfirmasi transaksi (Terima / Keluarkan)
     */
    public function confirmTransaction(int $id): RedirectResponse
    {
        $trx = StockTransaction::with('product')->findOrFail($id);

        if ($trx->status !== 'Pending') {
            return back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        $product = $trx->product;

        if ($trx->type === 'Masuk') {
            // Barang masuk: tambah stok, status jadi Diterima
            $product->increment('stock', $trx->quantity);
            $trx->update(['status' => 'Diterima']);
            $message = 'Barang masuk berhasil diterima dan stok diperbarui.';
        } else {
            // Barang keluar: cek stok cukup, kurangi stok, status jadi Dikeluarkan
            if ($product->stock < $trx->quantity) {
                return back()->with('error', 'Stok tidak mencukupi untuk pengeluaran ini.');
            }
            $product->decrement('stock', $trx->quantity);
            $trx->update(['status' => 'Dikeluarkan']);
            $message = 'Barang keluar berhasil dikonfirmasi dan stok diperbarui.';
        }

        return redirect()->route('staff.stock')->with('success', $message);
    }

    /**
     * Tolak transaksi
     */
    public function rejectTransaction(int $id): RedirectResponse
    {
        $trx = StockTransaction::findOrFail($id);

        if ($trx->status !== 'Pending') {
            return back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        $trx->update(['status' => 'Ditolak']);

        return redirect()->route('staff.stock')->with('success', 'Transaksi berhasil ditolak.');
    }
}
