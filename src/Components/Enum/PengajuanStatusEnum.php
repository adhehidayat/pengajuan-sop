<?php

namespace App\Components\Enum;

use Doctrine\Common\Collections\ArrayCollection;

enum PengajuanStatusEnum: string
{
    case MENUNGGU = 'Menunggu';
    case DALAM_PROSES = 'Dalam Proses';
    case DITERIMA = 'Diterima';
    case DITOLAK = 'Ditolak';
    case PERLU_PERBAIKAN = 'Perlu Perbaikan';

    case PROSES_PERBAIKAN = 'Proses Perbaikan';
    case PERBAIKAN_FIX = 'Perbaikan Selesai';

    case SUKSES = 'SUKSES';
    case GAGAL = 'GAGAL';

    public static function forRole(string $role, self $status): array
    {
        return match ($role) {
            'ROLE_OPERATOR' => match ($status) {
                self::DITERIMA => [
                    self::SUKSES
                ],
                self::DITOLAK => [
                    self::GAGAL
                ],
                self::PROSES_PERBAIKAN => [
                    self::PERBAIKAN_FIX
                ],
                default => []
            },
            'ROLE_OPERATOR_BIDANG' => [
                self::DITERIMA,
                self::DITOLAK,
                self::PERLU_PERBAIKAN,
//                self::DALAM_PROSES,
//                self::MENUNGGU
            ],
            default => [],
        };
    }

    public static function forFilter($role): array
    {
        return match ($role) {
            'ROLE_OPERATOR' => [
                self::DITERIMA,
                self::DITOLAK,
                self::PERLU_PERBAIKAN,
                self::PROSES_PERBAIKAN,
                self::SUKSES,
                self::GAGAL
            ],
            'ROLE_OPERATOR_BIDANG' => [
                self::DITERIMA,
                self::DITOLAK,
                self::PERLU_PERBAIKAN,
                self::DALAM_PROSES,
                self::MENUNGGU
            ],
            default => [],
        };
    }

    public static function forFields($roles, self $status): array
    {
        return array_combine(
            array_map(fn($s) => $s->label(), self::forRole($roles[0], $status)),
            self::forRole($roles[0], $status)
        );
    }


    public static function forChoices($roles, $options = 'roles' || 'filter'): array
    {
        return match ($options) {
            'roles' => array_combine(
                array_map(fn($s) => $s->label(), self::forRole($roles[0])),
                self::forRole($roles[0])
            ),
            'filter' => array_combine(
                array_map(fn($s) => $s->label(), self::forFilter($roles[0])),
                self::forFilter($roles[0])
            ),
            default => []
        };
    }

    public function label(): string
    {
        return $this->value;
    }

    public static function isEditable($roles, self $status): bool
    {
        return match ($roles[0]) {
            'ROLE_OPERATOR' => match ($status) {
                self::GAGAL, self::DITERIMA, self::DITOLAK, self::PROSES_PERBAIKAN => true,
                default => false
            },
            'ROLE_OPERATOR_BIDANG' => match ($status) {
                self::DALAM_PROSES, self::PERBAIKAN_FIX => true,
                default => false
            },
            default => false
        };
    }

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

    public static function getBadgeColors(): array
    {
        return [
            //danger
            self::MENUNGGU->value => "danger",
            self::DITOLAK->value => "danger",
            self::GAGAL->value => "danger",

            //success
            self::DITERIMA->value => "success",
            self::SUKSES->value => "success",
            self::PERBAIKAN_FIX->value => "success",

            // info
            self::DALAM_PROSES->value => "info",
            self::PERLU_PERBAIKAN->value => "info",
            self::PROSES_PERBAIKAN->value => "info",
        ];
    }

    public static function toArrayFixed(): ArrayCollection
    {

        return new ArrayCollection([
            self::PERBAIKAN_FIX->value => self::PERBAIKAN_FIX,
        ]);
    }
}






