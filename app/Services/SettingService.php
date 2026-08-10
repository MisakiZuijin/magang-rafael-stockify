<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class SettingService
{
    protected string $path;

    public function __construct()
    {
        $this->path = storage_path('app/settings.json');
    }

    public function all(): array
    {
        if (!File::exists($this->path)) {
            return $this->defaults();
        }

        return json_decode(File::get($this->path), true) ?? $this->defaults();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(array $data): void
    {
        $current = $this->all();
        $merged = array_merge($current, array_filter($data, fn($v) => $v !== null));
        File::put($this->path, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function uploadLogo($file, string $key = 'app_logo'): string
    {
        $dir = public_path('images/settings');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $filename = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        // Hapus logo lama kalau ada
        $old = $this->get($key);
        if ($old && File::exists(public_path($old))) {
            File::delete(public_path($old));
        }

        return 'images/settings/' . $filename;
    }

    protected function defaults(): array
    {
        return [
            'app_name' => 'Stockify',
            'app_logo' => null,
            'favicon' => null,
            'company_name' => '',
            'company_address' => '',
            'company_phone' => '',
            'company_email' => '',
        ];
    }
}
