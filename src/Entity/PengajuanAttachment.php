<?php

namespace App\Entity;

use App\Repository\PengajuanAttachmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PengajuanAttachmentRepository::class)]
#[Vich\Uploadable()]
class PengajuanAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $files = null;

    #[Vich\UploadableField(mapping: 'pengajuan_attachment', fileNameProperty: 'files')]
    private ?File $file;

    #[ORM\ManyToOne(targetEntity: Pengajuan::class, inversedBy: 'attachment')]
    private Pengajuan|null $pengajuan = null;

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

    public function getPengajuan(): ?Pengajuan
    {
        return $this->pengajuan;
    }

    public function setPengajuan(?Pengajuan $pengajuan): static
    {
        $this->pengajuan = $pengajuan;

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
