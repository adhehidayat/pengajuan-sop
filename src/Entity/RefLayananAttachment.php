<?php

namespace App\Entity;

use App\Repository\RefLayananAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RefLayananAttachmentRepository::class)]
#[Vich\Uploadable()]
class RefLayananAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_ref_layanan'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_ref_layanan'])]
    private ?string $files = null;

    #[ORM\ManyToOne(inversedBy: 'refLayananAttachments')]
    private ?RefLayanan $refLayanan = null;

    #[Vich\UploadableField(mapping: 'docs', fileNameProperty: 'files')]
    #[Groups(['get_ref_layanan'])]
    private ?File $file = null;

    public function __toString(): string
    {
        return $this->files ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFiles(): ?string
    {
        return $this->files;
    }

    public function setFiles(string $files): static
    {
        $this->files = $files;

        return $this;
    }

    public function getRefLayanan(): ?RefLayanan
    {
        return $this->refLayanan;
    }

    public function setRefLayanan(?RefLayanan $refLayanan): static
    {
        $this->refLayanan = $refLayanan;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;

        return $this;
    }
}
