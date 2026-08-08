<?php

namespace App\Http\Controllers;

use App\Services\ProductAttributService;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductAttributController extends Controller
{
    public function __construct(
        protected ProductAttributService $productAttributService,
        protected ProductService $productService,
    ) {}

    public function create(): View
    {
        $products = $this->productService->getAllProducts();
        return view('pages.admin.form.adminproduct-form-attribut', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'name'       => ['required', 'string', 'max:255'],
            'value'      => ['required', 'string', 'max:255'],
        ]);

        $this->productAttributService->create($validated);

        return redirect()->route('products.index')->with('success', 'Atribut produk berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $attribut = $this->productAttributService->getById($id);
        $products = $this->productService->getAllProducts();

        return view('pages.admin.form.adminproduct-form-attribut', compact('attribut', 'products'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'name'       => ['required', 'string', 'max:255'],
            'value'      => ['required', 'string', 'max:255'],
        ]);

        $this->productAttributService->update($id, $validated);

        return redirect()->route('products.index')->with('success', 'Atribut produk berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->productAttributService->delete($id);
        return redirect()->route('products.index')->with('success', 'Atribut produk berhasil dihapus.');
    }
}
