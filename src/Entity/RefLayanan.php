<?php

namespace App\Entity;

use App\Repository\RefLayananRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RefLayananRepository::class)]
class RefLayanan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_ref_layanan'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_ref_layanan'])]
    private ?string $nama = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['get_ref_layanan'])]
    private ?string $persyaratan = null;

    #[ORM\ManyToOne(inversedBy: 'refLayanan')]
    #[Groups(['get_ref_layanan'])]
    private ?RefPtsp $refPtsp = null;


    #[ORM\OneToMany(targetEntity: RefLayananAttachment::class, mappedBy: 'refLayanan', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Groups(['get_ref_layanan'])]
    #[MaxDepth(1)]
    private Collection $refLayananAttachments;

    public function __toString(): string
    {
        return $this->nama;
    }

    public function __construct()
    {
        $this->doc = new ArrayCollection();
        $this->refLayananAttachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPersyaratan(): ?string
    {
        return $this->persyaratan;
    }

    public function setPersyaratan(string $persyaratan): static
    {
        $this->persyaratan = $persyaratan;

        return $this;
    }

    public function getRefPtsp(): ?RefPtsp
    {
        return $this->refPtsp;
    }

    public function setRefPtsp(?RefPtsp $refPtsp): static
    {
        $this->refPtsp = $refPtsp;

        return $this;
    }

    /**
     * @return Collection<int, RefLayananAttachment>
     */
    public function getRefLayananAttachments(): Collection
    {
        return $this->refLayananAttachments;
    }

    public function addRefLayananAttachment(RefLayananAttachment $attachment): void
    {
        $this->refLayananAttachments->add($attachment);
        $attachment->setRefLayanan($this);

//        if (!$this->refLayananAttachments->contains($attachment)) {
//            $this->refLayananAttachments[] = $attachment;
//            $attachment->setRefLayanan($this);
//        }
//
//        return $this;
    }

    public function removeRefLayananAttachment(RefLayananAttachment $refLayananAttachment): static
    {
        if ($this->refLayananAttachments->removeElement($refLayananAttachment)) {
            // set the owning side to null (unless already changed)
            if ($refLayananAttachment->getRefLayanan() === $this) {
                $refLayananAttachment->setRefLayanan(null);
            }
        }

        return $this;
    }

}
