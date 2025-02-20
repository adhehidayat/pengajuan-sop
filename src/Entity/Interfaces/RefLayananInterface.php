<?php

namespace App\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;

interface RefLayananInterface
{
    public function getPersyaratan(): ?string;

    public function getRefLayananAttachments(): Collection;
}
