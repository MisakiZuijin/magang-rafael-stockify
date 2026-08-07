<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\View\View;

class SupplierController extends Controller
{

    public function __construct(
        protected SupplierService $userService
    ) {}

    /**
     * Tampilkan semua user (dengan pagination)
     */
    public function index(): View
    {
        $suppliers = $this->userService->getAllSuppliers();

        return view('pages.admin.adminproduct', compact('suppliers'));
    }

    /**
     * Tampilkan detail 1 user
     */
    public function show(int $id): View
    {
        $supplier = $this->userService->getSupplierById($id);

        return view('pages.test', compact('supplier'));
    }
}
