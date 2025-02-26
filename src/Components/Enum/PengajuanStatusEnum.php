<?php

namespace App\Components\Enum;

enum PengajuanStatusEnum: string
{
    CASE DALAM_PROSES = 'Dalam Proses';
    CASE DITERIMA = 'Diterima';
    CASE DITOLAK = 'Ditolak';
    CASE PERLU_PERBAIKAN = 'Perlu Perbaikan';

    public function level(): string
    {
        return match ($this) {
            self::DALAM_PROSES => 0,
            self::DITERIMA => 1,
            self::PERLU_PERBAIKAN => 2,
            self::DITOLAK => 3,

        };
    }

    public static function toArray(): array
    {
        return [
            self::DITERIMA->value => self::DITERIMA,
            self::DITOLAK->value => self::DITOLAK,
            self::PERLU_PERBAIKAN->value => self::PERLU_PERBAIKAN,
            self::DALAM_PROSES->value => self::DALAM_PROSES
        ];
    }
}
