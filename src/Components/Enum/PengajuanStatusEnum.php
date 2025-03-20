<?php

namespace App\Components\Enum;

use Doctrine\Common\Collections\ArrayCollection;

enum PengajuanStatusEnum: string
{
    CASE DALAM_PROSES = 'Dalam Proses';
    CASE DITERIMA = 'Diterima';
    CASE DITOLAK = 'Ditolak';
    CASE PERLU_PERBAIKAN = 'Perlu Perbaikan';

    CASE PROSES_PERBAIKAN = 'Proses Perbaikan';
    CASE PERBAIKAN_FIX = 'Perbaikan Selesai';

    public function level(): string
    {
        return match ($this) {
            self::DALAM_PROSES => 0,
            self::DITERIMA => 1,
            self::PERLU_PERBAIKAN => 2,
            self::DITOLAK => 3,
        };
    }

    public static function toArray(): ArrayCollection
    {

        return new ArrayCollection([
            self::DITERIMA->value => self::DITERIMA,
            self::DITOLAK->value => self::DITOLAK,
            self::PERLU_PERBAIKAN->value => self::PERLU_PERBAIKAN,
            self::DALAM_PROSES->value => self::DALAM_PROSES
        ]);
    }
}
