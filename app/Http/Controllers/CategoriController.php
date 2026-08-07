<?php

namespace App\Http\Controllers;

use App\Services\CategoriService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriController extends Controller
{
    public function __construct(
        protected CategoriService $categoryService
    ) {}

    public function create(): View
    {
        return view('pages.admin.adminproduct-form-categori');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categoris,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categoris,slug'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = str()->slug($validated['name']);
        }

        $this->categoryService->createCategory($validated);

        return redirect()->route('products.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $category = $this->categoryService->getCategoryById($id);
        return view('pages.admin.adminproduct-form-categori', compact('category'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categoris,name,' . $id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categoris,slug,' . $id],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = str()->slug($validated['name']);
        }

        $this->categoryService->updateCategory($id, $validated);

        return redirect()->route('products.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->categoryService->deleteCategory($id);
        return redirect()->route('products.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
