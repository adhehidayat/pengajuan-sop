<?php

namespace App\Entity;

use App\Repository\PengajuanProgressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PengajuanProgressRepository::class)]
class PengajuanProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ket;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\ManyToOne(inversedBy: 'progress')]
    private ?Pengajuan $pengajuan = null;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getPengajuan(): ?Pengajuan
    {
        return $this->pengajuan;
    }

    public function setPengajuan(?Pengajuan $pengajuan): static
    {
        $this->pengajuan = $pengajuan;

        return $this;
    }

    public function getKet(): ?string
    {
        return $this->ket;
    }

    public function setKet(string $ket): static
    {
        $this->ket = $ket;

        return $this;
    }
}
