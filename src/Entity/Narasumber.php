<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\NarasumberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NarasumberRepository::class)]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ['nik' => 'start'])]
class Narasumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $nik = null;

    #[ORM\Column(length: 255)]
    private ?string $nama = null;

    #[ORM\Column(unique: true)]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(length: 25, unique: true)]
    private ?string $telepon = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $alamat = null;

    public function __toString(): string
    {
        return $this->nama ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNik(): ?string
    {
        return $this->nik;
    }

    public function setNik(string $nik): static
    {
        $this->nik = $nik;

        return $this;
    }

    public function getNama(): ?string
    {
        return $this->nama;
    }

    public function setNama(string $nama): static
    {
        $this->nama = $nama;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelepon(): ?string
    {
        return $this->telepon;
    }

    public function setTelepon(string $telepon): static
    {
        $this->telepon = $telepon;

        return $this;
    }

    public function getAlamat(): ?string
    {
        return $this->alamat;
    }

    public function setAlamat(string $alamat): static
    {
        $this->alamat = $alamat;

        return $this;
    }
}
