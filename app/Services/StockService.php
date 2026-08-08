<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepo,
        protected ProductService $productService
    ) {}

    public function getAllTransactions(): Collection
    {
        return $this->transactionRepo->getAllWithRelations(['user', 'product']);
    }

    public function getRecentActivities(int $limit = 10): Collection
    {
        return $this->transactionRepo->getRecentActivities($limit);
    }

    /**
     * Stock Opname
     */
    public function opname(int $productId, int $actualStock, ?string $notes = null): void
    {
        $product = $this->productService->getProductById($productId);
        $difference = $actualStock - $product->stock;

        if ($difference === 0) {
            return;
        }

        DB::transaction(function () use ($productId, $difference, $actualStock, $notes) {
            $this->transactionRepo->create([
                'user_id'    => auth()->id(),
                'product_id' => $productId,
                'quantity'   => abs($difference),
                'type'       => $difference > 0 ? 'Masuk' : 'Keluar',
                'date'       => now()->toDateString(),
                'status'     => $difference > 0 ? 'Diterima' : 'Dikeluarkan',
                'note'       => $notes ?? 'Stock opname adjustment',
            ]);

            $this->productService->updateProduct($productId, [
                'stock' => $actualStock
            ]);
        });
    }

    /**
     * Set minimum stock (langsung ke kolom products)
     */
    public function setMinimumStock(int $productId, int $minimumStock): Product
    {
        return $this->productService->updateProduct($productId, [
            'minimum_stock' => $minimumStock
        ]);
    }

    /**
     * Produk dengan stock di bawah minimum
     */
    public function getLowStockProducts(): Collection
    {
        return $this->productService->getAllProducts()
            ->filter(fn($p) => $p->minimum_stock > 0 && $p->stock <= $p->minimum_stock);
    }
}
