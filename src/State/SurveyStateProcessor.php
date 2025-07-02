<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\PencarianPengajuan;
use App\Dto\Model\PencarianPengajuanModel;
use App\Entity\Pengajuan;
use App\Repository\PengajuanProgressHistoryRepository;
use App\Repository\PengajuanRepository;
use Doctrine\ORM\EntityManagerInterface;

class SurveyStateProcessor implements ProcessorInterface
{
    public function __construct(private readonly EntityManagerInterface             $entityManager,
                                private readonly PengajuanRepository                $pengajuanRepository,
                                private readonly PengajuanProgressHistoryRepository $pp_historyRepository)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PencarianPengajuanModel
    {
        $em = $this->entityManager;

        /** @var Pengajuan $pengajuan */
        $pengajuan = $data->getPengajuan();
        $pengajuan->setSurvey(true);

        $em->persist($pengajuan);
        $em->persist($data);
        $em->flush();

        $repository = $this->pengajuanRepository->findFilter($pengajuan->getContract(), $pengajuan->getUser()->getNik());

        $new = new PencarianPengajuanModel();

        $new->id = $repository[0]['id'];
        $new->contract = $repository[0]['contract'];
        $new->narasumber = (array)$repository[0]['narasumber'];
        $new->progress = $repository[0]['progress'];
        $new->historyProgress = $this->pp_historyRepository->findByPengajuan($repository[0]['id']);
        $new->files = !$repository[0]['files'] ? null : '/uploads/approved/' . $repository[0]['files'];
        $new->isSurvey = $repository[0]['survey'];

        return $new;
    }
}
