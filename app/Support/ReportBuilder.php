<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ReportBuilder
{
    public static function printedAt(): string
    {
        return now()->translatedFormat('d F Y H:i');
    }

    public static function printedBy(): ?string
    {
        return auth()->user()?->name;
    }

    public static function period(?string $from, ?string $to, ?string $single = null): string
    {
        if ($single) {
            return Carbon::parse($single)->translatedFormat('d F Y');
        }
        if ($from && $to) {
            return Carbon::parse($from)->translatedFormat('d F Y').' — '.Carbon::parse($to)->translatedFormat('d F Y');
        }
        if ($from) {
            return 'Sejak '.Carbon::parse($from)->translatedFormat('d F Y');
        }
        if ($to) {
            return 'Sampai '.Carbon::parse($to)->translatedFormat('d F Y');
        }
        return 'Semua Periode';
    }

    public static function money(mixed $value): string
    {
        if ($value === null || $value === '') return '-';
        return 'Rp '.number_format((float) $value, 0, ',', '.');
    }

    public static function date(mixed $value, string $format = 'd F Y'): string
    {
        if (! $value) return '-';
        return Carbon::parse($value)->translatedFormat($format);
    }

    public static function badge(string $value): string
    {
        $v = Str::lower($value);
        $cls = match (true) {
            in_array($v, ['published','approved','completed','selesai','success']) => 'badge--success',
            in_array($v, ['submitted','in_progress','proses','warning','scheduled','terjadwal']) => 'badge--warning',
            in_array($v, ['rejected','overdue','terlambat','cancelled','batal','danger']) => 'badge--danger',
            in_array($v, ['open','info']) => 'badge--info',
            default => 'badge--gray',
        };
        return '<span class="badge '.$cls.'">'.e(ucwords(str_replace(['_','-'], ' ', $value))).'</span>';
    }

    public static function meta(string $label, string $value): array
    {
        return ['label' => $label, 'value' => $value];
    }

    public static function safe(mixed $v, string $fallback = '-'): string
    {
        if ($v === null || $v === '' || $v === []) return $fallback;
        return (string) $v;
    }

    public static function signatures(?string $placeDate = null): array
    {
        $placeDate = $placeDate ?? ('Jakarta, '.now()->translatedFormat('d F Y'));
        return [
            ['role' => 'Dibuat Oleh', 'place_date' => $placeDate, 'name' => self::printedBy() ?? '...........................', 'note' => 'Pelapor / Teknisi'],
            ['role' => 'Diperiksa Oleh', 'place_date' => $placeDate, 'name' => '...........................', 'note' => 'Supervisor'],
            ['role' => 'Disetujui Oleh', 'place_date' => $placeDate, 'name' => '...........................', 'note' => 'Head of Engineering'],
        ];
    }
}
