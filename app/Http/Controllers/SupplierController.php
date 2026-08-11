<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService
    ) {}

    public function index(): View
    {
        $suppliers = $this->supplierService->getAllSuppliers();
        return view('pages.admin.adminsupplier', compact('suppliers'));
    }

    public function manager(Request $request): View
    {
        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        $suppliers = \App\Models\Supplier::withCount('products')->get();

        // Search
        if ($search) {
            $s = strtolower($search);
            $suppliers = $suppliers->filter(function ($sup) use ($s) {
                return str_contains(strtolower($sup->name), $s)
                    || str_contains(strtolower($sup->email ?? ''), $s)
                    || str_contains(strtolower($sup->phone ?? ''), $s)
                    || str_contains(strtolower($sup->address ?? ''), $s)
                    || str_contains(strtolower((string) $sup->id), $s);
            })->values();
        }

        // Sort
        $desc = $sortDirection === 'desc';
        $suppliers = match ($sortColumn) {
            'name'           => $suppliers->sortBy('name', SORT_REGULAR, $desc),
            'email'          => $suppliers->sortBy('email', SORT_REGULAR, $desc),
            'phone'          => $suppliers->sortBy('phone', SORT_REGULAR, $desc),
            'address'        => $suppliers->sortBy('address', SORT_REGULAR, $desc),
            'products_count' => $suppliers->sortBy('products_count', SORT_REGULAR, $desc),
            default          => $suppliers->sortBy('id', SORT_REGULAR, $desc),
        };

        return view('pages.manager.managersupplier', compact(
            'suppliers',
            'sortColumn',
            'sortDirection',
            'search'
        ));
    }

    public function create(): View
    {
        return view('pages.admin.form.adminsupplier-form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $this->supplierService->createSupplier($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $supplier = $this->supplierService->getSupplierById($id);
        return view('pages.admin.form.adminsupplier-form', compact('supplier'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $this->supplierService->updateSupplier($id, $validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->supplierService->deleteSupplier($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }

    public function full(Request $request): View
    {
        $query = \App\Models\Supplier::withCount('products');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $suppliers = $query->orderBy('id')->paginate(25)->withQueryString();
        return view('pages.admin.adminsupplier-full', compact('suppliers'));
    }
}
