<?php

namespace App\Entity;

use App\Repository\RefPtspRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: RefPtspRepository::class)]
class RefPtsp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_ref_layanan'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_ref_layanan'])]
    private ?string $nama = null;

    #[ORM\OneToMany(targetEntity: RefLayanan::class, mappedBy: 'refPtsp')]
    private Collection $refLayanan;

    public function __toString(): string
    {
        return $this->nama;
    }

    public function __construct()
    {
        $this->refLayanan = new ArrayCollection();
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

    /**
     * @return Collection<int, RefLayanan>
     */
    public function getRefLayanan(): Collection
    {
        return $this->refLayanan;
    }

    public function addRefLayanan(RefLayanan $refLayanan): static
    {
        if (!$this->refLayanan->contains($refLayanan)) {
            $this->refLayanan->add($refLayanan);
            $refLayanan->setRefPtsp($this);
        }

        return $this;
    }

    public function removeRefLayanan(RefLayanan $refLayanan): static
    {
        if ($this->refLayanan->removeElement($refLayanan)) {
            // set the owning side to null (unless already changed)
            if ($refLayanan->getRefPtsp() === $this) {
                $refLayanan->setRefPtsp(null);
            }
        }

        return $this;
    }
}
