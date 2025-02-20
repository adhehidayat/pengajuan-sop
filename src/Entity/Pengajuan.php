<?php

namespace App\Entity;

use App\Entity\Interfaces\RefLayananInterface;
use App\Repository\PengajuanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PengajuanRepository::class)]
class Pengajuan implements RefLayananInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private string $contract;

    #[ORM\Column(length: 255)]
    private ?string $nama = null;

    #[ORM\Column(length: 25)]
    private ?string $telepon = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $alamat = null;

    #[ORM\OneToMany(targetEntity: PengajuanAttachment::class, mappedBy: 'pengajuan', cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection|null $attachment = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\ManyToOne]
    private RefLayanan $layanan;

    #[ORM\ManyToOne]
    private RefPtsp $ptsp;

    public function __construct()
    {
        $this->attachment = new ArrayCollection();
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): static
    {
        $this->contract = $contract;

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

    public function getLayanan(): ?RefLayanan
    {
        return $this->layanan;
    }

    public function setLayanan(?RefLayanan $layanan): static
    {
        $this->layanan = $layanan;

        return $this;
    }

    public function getPtsp(): ?RefPtsp
    {
        return $this->ptsp;
    }

    public function setPtsp(?RefPtsp $ptsp): static
    {
        $this->ptsp = $ptsp;

        return $this;
    }

    public function getPersyaratan(): ?string
    {
        return $this->layanan->getPersyaratan();
    }

    public function getRefLayananAttachments(): Collection
    {
        return $this->layanan->getRefLayananAttachments();
    }

    /**
     * @return Collection<int, PengajuanAttachment>
     */
    public function getAttachment(): Collection
    {
        return $this->attachment;
    }

    public function addAttachment(PengajuanAttachment $attachment): void
    {
        $this->attachment->add($attachment);
    }

    public function removeAttachment(PengajuanAttachment $attachment): static
    {
        if ($this->attachment->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getPengajuan() === $this) {
                $attachment->setPengajuan(null);
            }
        }

        return $this;
    }


}
