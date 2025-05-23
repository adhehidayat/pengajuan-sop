<?php
declare(strict_types=1);
namespace App\Dto\Model\Layanan;

final class DetailLayanan
{
    public function __construct(
        public int $id,
        public string $nama,
        public ?string $persyaratan,
        public ?array $layananAttachments,
    )
    {
    }
}