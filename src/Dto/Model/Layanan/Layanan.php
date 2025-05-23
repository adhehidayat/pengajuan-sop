<?php
declare(strict_types=1);
namespace App\Dto\Model\Layanan;

final class Layanan
{
    public function __construct(
        public int $id,
        public string $kode,
        public string $title,
        /** @var RequirementLayanan[] */
        public array $requirement,
    )
    {
    }
}