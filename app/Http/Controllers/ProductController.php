<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\CategoriService;
use App\Services\SupplierService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoriService $categoryService,
        protected SupplierService $supplierService,
    ) {}

    public function index(): View
    {
        $products = $this->productService->getAllProducts();
        $categories = $this->categoryService->getAllCategories();
        return view('pages.admin.adminproduct', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = $this->categoryService->getAllCategories();
        $suppliers  = $this->supplierService->getAllSuppliers();

        return view('pages.admin.adminproduct-form-product', compact('categories', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id'    => ['required', 'integer', 'exists:categoris,id'],
            'supplier_id'    => ['required', 'integer', 'exists:suppliers,id'],
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'sku'            => ['required', 'string', 'max:100', 'unique:products,sku'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $this->productService->createProduct($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(int $id): View
    {
        $product = $this->productService->getProductById($id);
        return view('pages.admin.adminproduct', compact('product'));
    }

    public function edit(int $id): View
    {
        $product    = $this->productService->getProductById($id);
        $categories = $this->categoryService->getAllCategories();
        $suppliers  = $this->supplierService->getAllSuppliers();

        return view('pages.admin.adminproduct-form-product', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'category_id'    => ['required', 'integer', 'exists:categoris,id'],
            'supplier_id'    => ['required', 'integer', 'exists:suppliers,id'],
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'sku'            => ['required', 'string', 'max:100', 'unique:products,sku,' . $id],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $this->productService->updateProduct($id, $validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->productService->deleteProduct($id);
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
