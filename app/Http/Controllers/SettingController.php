<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index(): View
    {
        $settings = $this->settingService->all();
        return view('pages.admin.adminsetting', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name'        => ['required', 'string', 'max:255'],
            'company_name'    => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string'],
            'company_phone'   => ['nullable', 'string', 'max:50'],
            'company_email'   => ['nullable', 'email', 'max:255'],
            'app_logo'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'favicon'         => ['nullable', 'image', 'mimes:png,ico', 'max:1024'],
        ]);

        // Handle text fields
        $data = [
            'app_name'        => $validated['app_name'],
            'company_name'    => $validated['company_name'] ?? '',
            'company_address' => $validated['company_address'] ?? '',
            'company_phone'   => $validated['company_phone'] ?? '',
            'company_email'   => $validated['company_email'] ?? '',
        ];

        // Handle uploads
        if ($request->hasFile('app_logo')) {
            $data['app_logo'] = $this->settingService->uploadLogo($request->file('app_logo'), 'app_logo');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $this->settingService->uploadLogo($request->file('favicon'), 'favicon');
        }

        $this->settingService->set($data);

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
