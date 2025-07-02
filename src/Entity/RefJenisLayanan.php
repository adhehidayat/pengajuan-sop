<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\Model\Layanan\Layanan;
use App\Repository\RefJenisLayananRepository;
use App\State\LayananGroupProvider;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefJenisLayananRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/public/layanan_group',
            output: Layanan::class,
            provider: LayananGroupProvider::class
        )
    ],
    paginationEnabled: false
)]
class RefJenisLayanan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $kode = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKode(): ?string
    {
        return $this->kode;
    }

    public function setKode(string $kode): static
    {
        $this->kode = $kode;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
