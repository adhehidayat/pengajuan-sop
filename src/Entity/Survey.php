<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use App\Dto\Model\PencarianPengajuanModel;
use App\Repository\SurveyRepository;
use App\State\SurveyStateProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SurveyRepository::class)]
#[Post(
    uriTemplate: '/public/surveys',
    outputFormats: ['json' => ['application/json']],
    output: PencarianPengajuanModel::class,
    processor: SurveyStateProcessor::class
)]
class Survey
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    private Narasumber $narasumber;

    #[ORM\ManyToOne]
    private Pengajuan $pengajuan;

    #[ORM\Column]
    private int $que1;

    #[ORM\Column]
    private int $que2;

    #[ORM\Column]
    private int $que3;

    #[ORM\Column]
    private int $que4;

    #[ORM\Column]
    private int $que5;

    #[ORM\Column]
    private int $que6;

    #[ORM\Column]
    private int $que7;

    #[ORM\Column]
    private int $que8;

    #[ORM\Column]
    private int $que9;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createAt;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getQue1(): ?int
    {
        return $this->que1;
    }

    public function setQue1(int $que1): static
    {
        $this->que1 = $que1;

        return $this;
    }

    public function getQue2(): ?int
    {
        return $this->que2;
    }

    public function setQue2(int $que2): static
    {
        $this->que2 = $que2;

        return $this;
    }

    public function getQue3(): ?int
    {
        return $this->que3;
    }

    public function setQue3(int $que3): static
    {
        $this->que3 = $que3;

        return $this;
    }

    public function getQue4(): ?int
    {
        return $this->que4;
    }

    public function setQue4(int $que4): static
    {
        $this->que4 = $que4;

        return $this;
    }

    public function getQue5(): ?int
    {
        return $this->que5;
    }

    public function setQue5(int $que5): static
    {
        $this->que5 = $que5;

        return $this;
    }

    public function getQue6(): ?int
    {
        return $this->que6;
    }

    public function setQue6(int $que6): static
    {
        $this->que6 = $que6;

        return $this;
    }

    public function getQue7(): ?int
    {
        return $this->que7;
    }

    public function setQue7(int $que7): static
    {
        $this->que7 = $que7;

        return $this;
    }

    public function getQue8(): ?int
    {
        return $this->que8;
    }

    public function setQue8(int $que8): static
    {
        $this->que8 = $que8;

        return $this;
    }

    public function getQue9(): ?int
    {
        return $this->que9;
    }

    public function setQue9(int $que9): static
    {
        $this->que9 = $que9;

        return $this;
    }

    public function getNarasumber(): ?Narasumber
    {
        return $this->narasumber;
    }

    public function setNarasumber(?Narasumber $narasumber): static
    {
        $this->narasumber = $narasumber;

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

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): void
    {
        $this->createAt = $createAt;
    }
}
