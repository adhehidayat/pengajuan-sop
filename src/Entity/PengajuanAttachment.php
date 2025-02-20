<?php

namespace App\Entity;

use App\Repository\PengajuanAttachmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
    private ?string $file = null;

    #[Vich\UploadableField(mapping: 'pengajuan_attachment', fileNameProperty: 'file')]
    private UploadedFile $files;

    #[ORM\ManyToOne(targetEntity: Pengajuan::class, inversedBy: 'attachment')]
    private Pengajuan|null $pengajuan = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

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

}
