<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\CategoriService;
use App\Services\SupplierService;
use App\Services\ProductAttributService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoriService $categoryService,
        protected SupplierService $supplierService,
        protected ProductAttributService $productAttributService
    ) {}

    public function index(): View
    {
        $products = $this->productService->getAllProducts();
        $categories = $this->categoryService->getAllCategories();
        $productAttributs = $this->productAttributService->getAll();
        return view('pages.admin.adminproduct', compact('products', 'categories', 'productAttributs'));
    }

    public function managerIndex(Request $request): View
    {
        $products = $this->productService->getAllProducts();

        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        // Search
        if ($search) {
            $s = strtolower($search);
            $products = $products->filter(function ($p) use ($s) {
                return str_contains(strtolower($p->name), $s)
                    || str_contains(strtolower($p->sku ?? ''), $s)
                    || str_contains(strtolower($p->categori?->name ?? ''), $s)
                    || str_contains(strtolower((string) $p->id), $s);
            })->values();
        }

        // Sort
        $desc = $sortDirection === 'desc';
        $products = match ($sortColumn) {
            'name'          => $products->sortBy('name', SORT_REGULAR, $desc),
            'category'      => $products->sortBy(fn($p) => $p->categori?->name ?? '', SORT_REGULAR, $desc),
            'selling_price' => $products->sortBy('selling_price', SORT_REGULAR, $desc),
            'stock'         => $products->sortBy('stock', SORT_REGULAR, $desc),
            default         => $products->sortBy('id', SORT_REGULAR, $desc),
        };

        return view('pages.manager.managerproduct', compact(
            'products',
            'sortColumn',
            'sortDirection',
            'search'
        ));
    }

    public function create(): View
    {
        $categories = $this->categoryService->getAllCategories();
        $suppliers  = $this->supplierService->getAllSuppliers();

        if (auth()->user()->role === 'Manager Gudang') {
            return view('pages.manager.form.managerproduct-form-product', compact('categories', 'suppliers'));
        }

        return view('pages.admin.form.adminproduct-form-product', compact('categories', 'suppliers'));
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
            'stock'     => ['required', 'integer', 'min:0'],
            'minimum_stock'          => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        // if ($request->hasFile('image')) {
        //     $validated['image'] = $request->file('image')->store('products', 'public');
        // }

        $this->productService->createProduct($validated);

        $redirectRoute = auth()->user()->role === 'Manager Gudang' ? 'manager.products' : 'products.index';

        return redirect()->route($redirectRoute)->with('success', 'Produk berhasil ditambahkan.');
        // return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(int $id): View
    {
        $product = $this->productService->getProductById($id);

        // Tentukan back route berdasarkan role user
        $backRoute = auth()->user()->role === 'Manager Gudang'
            ? route('manager.products')
            : route('dashboard');

        return view('pages.show', [
            'title'       => 'Detail Produk',
            'subtitle'    => 'SKU: ' . $product->sku,
            'backRoute'   => $backRoute,
            'editRoute'   => $backRoute ? null : route('products.edit', $product->id),
            'deleteRoute' => $backRoute ? null : route('products.destroy', $product->id),
            'fields'      => [
                ['label' => 'Gambar', 'value' => $product->image ? asset('storage/' . $product->image) : null, 'type' => 'image'],
                ['label' => 'Nama Produk', 'value' => $product->name],
                ['label' => 'SKU', 'value' => $product->sku],
                ['label' => 'Kategori', 'value' => $product->categori?->name],
                ['label' => 'Supplier', 'value' => $product->supplier?->name],
                ['label' => 'Harga Beli', 'value' => $product->purchase_price, 'type' => 'money'],
                ['label' => 'Harga Jual', 'value' => $product->selling_price, 'type' => 'money'],
                ['label' => 'Stok Tersedia', 'value' => $product->stock, 'type' => 'stock'],
                ['label' => 'Stok Minimum', 'value' => $product->minimum_stock],
                ['label' => 'Deskripsi', 'value' => $product->description],
            ],
        ]);
    }

    public function edit(int $id): View
    {
        $product    = $this->productService->getProductById($id);
        $categories = $this->categoryService->getAllCategories();
        $suppliers  = $this->supplierService->getAllSuppliers();

        if (auth()->user()->role === 'Manager Gudang') {
            return view('pages.manager.form.managerproduct-form-product', compact('product', 'categories', 'suppliers'));
        }

        return view('pages.admin.form.adminproduct-form-product', compact('product', 'categories', 'suppliers'));
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
            'stock'     => ['required', 'integer', 'min:0'],
            'minimum_stock'     => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $product = $this->productService->getProductById($id);

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('images/' . $product->image))) {
                unlink(public_path('images/' . $product->image));
            }

            $file     = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        // if ($request->hasFile('image')) {
        //     $validated['image'] = $request->file('image')->store('products', 'public');
        // }

        $this->productService->updateProduct($id, $validated);

        $redirectRoute = auth()->user()->role === 'Manager Gudang' ? 'manager.products' : 'products.index';

        return redirect()->route($redirectRoute)->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = $this->productService->getProductById($id);

        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $this->productService->deleteProduct($id);

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function full(Request $request): View
    {
        $query = Product::with('categori');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('id')->paginate(25)->withQueryString();

        return view('pages.admin.fullview.adminproduct-full', compact('products'));
    }

    public function export(): StreamedResponse
    {
        $products = Product::with(['categori', 'supplier'])->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="produk_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // BOM agar Excel membaca UTF-8 dengan benar
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($file, [
                'ID',
                'Nama',
                'SKU',
                'Kategori_ID',
                'Supplier_ID',
                'Harga_Beli',
                'Harga_Jual',
                'Stok',
                'Stok_Minimum',
                'Deskripsi'
            ]);

            foreach ($products as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->name,
                    $p->sku,
                    $p->category_id,
                    $p->supplier_id,
                    $p->purchase_price,
                    $p->selling_price,
                    $p->stock,
                    $p->minimum_stock,
                    $p->description ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import produk dari CSV
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file   = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Lewati BOM UTF-8 kalau ada
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Baca header
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }

        $count   = 0;
        $errors  = [];
        $rowNum  = 1; // baris 0 = header

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            if (count($row) < 10) {
                continue;
            }

            // Mapping berdasarkan urutan kolom export
            $id             = trim($row[0] ?? '');
            $name           = trim($row[1] ?? '');
            $sku            = trim($row[2] ?? '');
            $categoryId     = trim($row[3] ?? '');
            $supplierId     = trim($row[4] ?? '');
            $purchasePrice  = trim($row[5] ?? '');
            $sellingPrice   = trim($row[6] ?? '');
            $stock          = trim($row[7] ?? '');
            $minimumStock   = trim($row[8] ?? '');
            $description    = trim($row[9] ?? '');

            if (empty($name) || empty($sku)) {
                $errors[] = "Baris {$rowNum}: Nama dan SKU wajib diisi.";
                continue;
            }

            // Cek SKU duplikat (kalau insert baru)
            $existing = Product::where('sku', $sku)->first();

            $data = [
                'name'           => $name,
                'sku'            => $sku,
                'category_id'    => is_numeric($categoryId) ? (int) $categoryId : null,
                'supplier_id'    => is_numeric($supplierId) ? (int) $supplierId : null,
                'purchase_price' => (float) str_replace('.', '', $purchasePrice),
                'selling_price'  => (float) str_replace('.', '', $sellingPrice),
                'stock'          => (int) $stock,
                'minimum_stock'  => (int) $minimumStock,
                'description'    => $description,
            ];

            if ($existing) {
                $existing->update($data);
                $count++;
            } else {
                Product::create($data);
                $count++;
            }
        }

        fclose($handle);

        $message = "{$count} produk berhasil diimpor.";
        if (!empty($errors)) {
            $errorMsg = implode(', ', array_slice($errors, 0, 3));
            if (count($errors) > 3) {
                $errorMsg .= ' (+' . (count($errors) - 3) . ' error lainnya)';
            }
            return back()->with('success', $message)->with('error', $errorMsg);
        }

        return back()->with('success', $message);
    }
}
