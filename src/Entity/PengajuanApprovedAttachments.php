<?php

namespace App\Entity;

use App\Repository\PengajuanApprovedAttachmentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PengajuanApprovedAttachmentsRepository::class)]
#[Vich\Uploadable()]
class PengajuanApprovedAttachments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $files = null;

    #[Vich\UploadableField(mapping: 'approved_bidang', fileNameProperty: 'files')]
    private ?File $file = null;

    #[ORM\ManyToOne(targetEntity: Pengajuan::class, inversedBy: 'approvedAttachments')]
    private ?Pengajuan $pengajuanAttachment;

    public function __toString(): string
    {
        return $this->files;
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

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getPengajuanAttachment(): ?Pengajuan
    {
        return $this->pengajuanAttachment;
    }

    public function setPengajuanAttachment(?Pengajuan $pengajuanAttachment): static
    {
        $this->pengajuanAttachment = $pengajuanAttachment;

        return $this;
    }
}
