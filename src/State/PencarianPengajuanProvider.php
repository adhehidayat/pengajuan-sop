<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PartialPaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\PencarianPengajuan;
use App\Repository\PengajuanProgressHistoryRepository;
use App\Repository\PengajuanRepository;
use Doctrine\Common\Collections\ArrayCollection;

final class PencarianPengajuanProvider implements ProviderInterface
{
    public function __construct(private PengajuanRepository $pengajuanRepository, private PengajuanProgressHistoryRepository $pp_historyRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $filters = $context['filters'] ?? [];

        $contract = $filters['contract'] ?? null;
        $nik = $filters['user.nik'] ?? null;

        $repository = $this->pengajuanRepository->findFilter($contract, $nik);


        $results = [];
        foreach ($repository as $repo) {
            $new = new PencarianPengajuan();

            $new->id = $repo['id'];
            $new->contract = $repo['contract'];
            $new->narasumber = (array)$repo['narasumber'];
            $new->progress = $repo['progress'];
            $new->historyProgress = $this->pp_historyRepository->findByPengajuan($repo['id']);
            $new->files = !$repo['files'] ? null : '/uploads/approved/' . $repo['files'];
            $new->isSurvey = $repo['survey'];
            $results[] = $new;
        }

        return $results;
    }

}