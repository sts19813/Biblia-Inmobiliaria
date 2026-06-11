<?php

namespace App\Support;

use App\Models\DevelopmentDocumentFile;

class MiniDriveStorageUsage
{
    public static function humanBytes(int $bytes): string
    {
        return DevelopmentDocumentFile::humanBytes($bytes);
    }

    public function summary(): array
    {
        $usedBytes = $this->usedBytes();
        $limitGb = SystemSettings::miniDriveStorageLimitGb();
        $limitBytes = (int) round($limitGb * 1024 * 1024 * 1024);
        $percentage = $limitBytes > 0 ? min(100, ($usedBytes / $limitBytes) * 100) : 0;
        $availableBytes = max(0, $limitBytes - $usedBytes);

        return [
            'used_bytes' => $usedBytes,
            'used_label' => self::humanBytes($usedBytes),
            'used_exact_label' => number_format($usedBytes).' bytes',
            'limit_gb' => $limitGb,
            'limit_bytes' => $limitBytes,
            'limit_label' => $limitBytes > 0 ? $this->gigabytesLabel($limitGb) : 'Sin limite',
            'available_bytes' => $availableBytes,
            'available_label' => self::humanBytes($availableBytes),
            'percentage' => $percentage,
            'percentage_label' => $this->percentageLabel($percentage),
            'is_over_limit' => $limitBytes > 0 && $usedBytes > $limitBytes,
        ];
    }

    private function usedBytes(): int
    {
        return (int) DevelopmentDocumentFile::query()
            ->where('disk', 'public')
            ->where('path', 'like', 'development-documents/%')
            ->sum('size_bytes');
    }

    private function gigabytesLabel(float $gigabytes): string
    {
        $decimals = floor($gigabytes) === $gigabytes ? 0 : 1;

        return number_format($gigabytes, $decimals).' GB';
    }

    private function percentageLabel(float $percentage): string
    {
        if ($percentage > 0 && $percentage < 0.01) {
            return '<0.01';
        }

        return number_format($percentage, $percentage > 0 && $percentage < 1 ? 2 : 1);
    }
}
