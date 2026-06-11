<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\MiniDriveStorageUsage;
use App\Support\SystemSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'settings' => [
                'system_name' => SystemSettings::get('general.system_name'),
                'support_email' => SystemSettings::get('general.support_email'),
                'mini_drive_storage_limit_gb' => SystemSettings::miniDriveStorageLimitGb(),
                'mini_drive_max_file_size_mb' => SystemSettings::miniDriveMaxFileSizeMb(),
                'mini_drive_default_visibility' => SystemSettings::miniDriveDefaultVisibility(),
                'mini_drive_storage_warning_percent' => (int) SystemSettings::get('mini_drive.storage_warning_percent'),
            ],
            'miniDriveStorage' => app(MiniDriveStorageUsage::class)->summary(),
            'miniDriveUploadLimit' => SystemSettings::miniDriveUploadLimit(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'system_name' => ['required', 'string', 'max:120'],
            'support_email' => ['nullable', 'email', 'max:150'],
            'mini_drive_storage_limit_gb' => ['required', 'numeric', 'min:1', 'max:10240'],
            'mini_drive_max_file_size_mb' => ['required', 'integer', 'min:1', 'max:2048'],
            'mini_drive_default_visibility' => ['required', 'in:public,private'],
            'mini_drive_storage_warning_percent' => ['required', 'integer', 'min:50', 'max:100'],
        ], [
            'mini_drive_storage_limit_gb.min' => 'La capacidad del minidrive debe ser de al menos 1 GB.',
            'mini_drive_max_file_size_mb.min' => 'El tamano maximo por archivo debe ser de al menos 1 MB.',
            'mini_drive_default_visibility.in' => 'Selecciona una visibilidad valida para los archivos nuevos.',
        ]);

        SystemSettings::setMany([
            'general.system_name' => $data['system_name'],
            'general.support_email' => $data['support_email'] ?? '',
            'mini_drive.storage_limit_gb' => $data['mini_drive_storage_limit_gb'],
            'mini_drive.max_file_size_mb' => $data['mini_drive_max_file_size_mb'],
            'mini_drive.default_visibility' => $data['mini_drive_default_visibility'],
            'mini_drive.storage_warning_percent' => $data['mini_drive_storage_warning_percent'],
        ]);

        return redirect()
            ->route('admin.settings')
            ->with('status', 'Configuraciones guardadas correctamente.');
    }
}
