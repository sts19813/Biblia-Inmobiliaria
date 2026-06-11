<?php

namespace App\Support;

use App\Models\SystemSetting;

class SystemSettings
{
    public static function get(string $key, mixed $fallback = null): mixed
    {
        $setting = SystemSetting::query()->where('key', $key)->value('value');

        if ($setting !== null) {
            return $setting;
        }

        return $fallback ?? self::defaults()[$key] ?? null;
    }

    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            SystemSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value]
            );
        }
    }

    public static function miniDriveStorageLimitGb(): float
    {
        return max(0, (float) self::get('mini_drive.storage_limit_gb'));
    }

    public static function miniDriveMaxFileSizeMb(): int
    {
        return max(1, (int) self::get('mini_drive.max_file_size_mb'));
    }

    public static function miniDriveDefaultVisibility(): string
    {
        $visibility = (string) self::get('mini_drive.default_visibility', 'public');

        return in_array($visibility, ['public', 'private'], true) ? $visibility : 'public';
    }

    public static function miniDriveUploadLimit(): array
    {
        $configuredBytes = self::miniDriveMaxFileSizeMb() * 1024 * 1024;
        $serverBytes = self::serverUploadLimitBytes();
        $effectiveBytes = $serverBytes > 0 ? min($configuredBytes, $serverBytes) : $configuredBytes;

        return [
            'configured_bytes' => $configuredBytes,
            'configured_label' => MiniDriveStorageUsage::humanBytes($configuredBytes),
            'server_bytes' => $serverBytes,
            'server_label' => $serverBytes > 0 ? MiniDriveStorageUsage::humanBytes($serverBytes) : 'Sin limite detectado',
            'effective_bytes' => $effectiveBytes,
            'effective_kilobytes' => max(1, (int) floor($effectiveBytes / 1024)),
            'effective_label' => MiniDriveStorageUsage::humanBytes($effectiveBytes),
            'is_server_limited' => $serverBytes > 0 && $serverBytes < $configuredBytes,
        ];
    }

    public static function defaults(): array
    {
        return [
            'general.system_name' => config('app.name', 'Biblia Inmobiliaria'),
            'general.support_email' => config('mail.from.address', 'hello@example.com'),
            'mini_drive.storage_limit_gb' => config('filesystems.mini_drive.storage_limit_gb', 50),
            'mini_drive.max_file_size_mb' => config('filesystems.mini_drive.max_file_size_mb', 50),
            'mini_drive.default_visibility' => 'public',
            'mini_drive.storage_warning_percent' => 80,
        ];
    }

    private static function serverUploadLimitBytes(): int
    {
        $uploadMax = self::iniBytes((string) ini_get('upload_max_filesize'));
        $postMax = self::iniBytes((string) ini_get('post_max_size'));
        $limits = array_filter([$uploadMax, $postMax], fn (int $bytes) => $bytes > 0);

        return $limits === [] ? 0 : min($limits);
    }

    private static function iniBytes(string $value): int
    {
        $value = trim($value);

        if ($value === '') {
            return 0;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return match ($unit) {
            'g' => (int) ($number * 1024 * 1024 * 1024),
            'm' => (int) ($number * 1024 * 1024),
            'k' => (int) ($number * 1024),
            default => (int) $number,
        };
    }
}
