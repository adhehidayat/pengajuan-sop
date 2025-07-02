<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Interfaces\RefLayananInterface;
use App\Repository\PengajuanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PengajuanRepository::class)]
#[GetCollection(
    uriTemplate: 'public/pengajuans'
)]
#[ApiFilter(SearchFilter::class, properties: ['user.nik' => 'partial', 'contract' => 'partial'])]
class Pengajuan implements RefLayananInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $contract;

    #[ORM\Column(nullable: true)]
    private ?string $instansi;

    #[ORM\ManyToOne]
    private Narasumber $user;

    #[
        ORM\OneToMany(
            targetEntity: PengajuanAttachment::class,
            mappedBy: "pengajuan",
            cascade: ["persist", "remove"],
            orphanRemoval: true
        )
    ]
    private ?Collection $attachment;

    #[ORM\ManyToOne]
    private RefLayanan $layanan;

    #[ORM\ManyToOne]
    private RefPtsp $ptsp;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\OneToOne(inversedBy: "pengajuan", cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: true)]
    private ?PengajuanProgress $pengajuanProgress;

    #[ORM\OneToMany(targetEntity: PengajuanApprovedAttachments::class, mappedBy: 'pengajuanAttachment', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Collection $approvedAttachments;

    #[ORM\Column]
    private ?bool $survey = false;

    public function __construct()
    {
        $this->attachment = new ArrayCollection();
        $this->createAt = new \DateTimeImmutable();
        $this->approvedAttachments = new ArrayCollection();
    }

    #[Groups(["pencarian_read", 'ptsp_read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(["pencarian_read", 'ptsp_read'])]
    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): static
    {
        $this->contract = $contract;

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

    #[Groups(["pencarian_read", 'ptsp_read'])]
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
        $attachment->setPengajuan($this);
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

    public function getUser(): ?Narasumber
    {
        return $this->user;
    }

    public function setUser(?Narasumber $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getInstansi(): ?string
    {
        return $this->instansi;
    }

    public function setInstansi(?string $instansi): static
    {
        $this->instansi = $instansi;

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

    public function getPengajuanProgress(): ?PengajuanProgress
    {
        return $this->pengajuanProgress;
    }

    public function setPengajuanProgress(
        PengajuanProgress $pengajuanProgress
    ): static
    {
        $this->pengajuanProgress = $pengajuanProgress;

        return $this;
    }

    /**
     * @return Collection<int, PengajuanApprovedAttachments>
     */
    public function getApprovedAttachments(): Collection
    {
        return $this->approvedAttachments;
    }

    public function addApprovedAttachment(PengajuanApprovedAttachments $approvedAttachment): static
    {
        if (!$this->approvedAttachments->contains($approvedAttachment)) {
            $this->approvedAttachments->add($approvedAttachment);
            $approvedAttachment->setPengajuanAttachment($this);
        }

        return $this;
    }

    public function removeApprovedAttachment(PengajuanApprovedAttachments $approvedAttachment): static
    {
        if ($this->approvedAttachments->removeElement($approvedAttachment)) {
            // set the owning side to null (unless already changed)
            if ($approvedAttachment->getPengajuanAttachment() === $this) {
                $approvedAttachment->setPengajuanAttachment(null);
            }
        }

        return $this;
    }

    public function isSurvey(): ?bool
    {
        return $this->survey;
    }

    public function setSurvey(bool $survey): static
    {
        $this->survey = $survey;

        return $this;
    }
}
