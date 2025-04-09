<?php

namespace App\Entity;

use App\Components\Enum\PengajuanStatusEnum;
use App\Repository\PengajuanProgressRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PengajuanProgressRepository::class)]
class PengajuanProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: PengajuanStatusEnum::class)]
    private ?PengajuanStatusEnum $status = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ket = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?PengajuanStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?PengajuanStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
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

    public function getKet(): ?string
    {
        return $this->ket;
    }

    public function setKet(?string $ket): static
    {
        $this->ket = $ket;

        return $this;
    }

    private ?array $attachmentPengajuan;

    #[ORM\OneToOne(mappedBy: 'pengajuanProgress', cascade: ['persist', 'remove'])]
    private ?Pengajuan $pengajuan = null;

    /**
     * @return array|null
     */
    public function getAttachmentPengajuan(): ?array
    {
        return $this->attachmentPengajuan;
    }

    /**
     * @param array|null $attachmentPengajuan
     */
    public function setAttachmentPengajuan(?array $attachmentPengajuan): void
    {
        $this->attachmentPengajuan = $attachmentPengajuan;
    }

    public function getPengajuan(): ?Pengajuan
    {
        return $this->pengajuan;
    }

    public function setPengajuan(Pengajuan $pengajuan): static
    {
        // set the owning side of the relation if necessary
        if ($pengajuan->getPengajuanProgress() !== $this) {
            $pengajuan->setPengajuanProgress($this);
        }

        $this->pengajuan = $pengajuan;

        return $this;
    }
}
