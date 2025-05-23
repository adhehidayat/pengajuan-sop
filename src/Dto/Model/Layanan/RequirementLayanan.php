<?php

namespace App\Dto\Model\Layanan;

final class RequirementLayanan
{
    public function __construct(
        public int $id, // misalnya '/api/layananPtsp/1'
        public ?string $title,
        public ?string $icon,
        public ?string $color,
        public ?string $description,
        public ?string $link,
        /** @var DetailLayanan[] */
        public array $detail,
    )
    {
    }
}