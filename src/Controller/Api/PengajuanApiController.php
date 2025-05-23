<?php

namespace App\Controller\Api;

use App\Components\Enum\PengajuanStatusEnum;
use App\Entity\Pengajuan;
use App\Entity\PengajuanAttachment;
use App\Entity\PengajuanProgress;
use App\Entity\RefLayanan;
use App\Repository\NarasumberRepository;
use App\Repository\PengajuanRepository;
use App\Repository\RefLayananRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PengajuanApiController extends AbstractController
{

    public function __invoke(Request $request, PengajuanRepository $pengajuanRepository, RefLayananRepository $refLayananRepository, NarasumberRepository $narasumberRepository): JsonResponse
    {
        $files = $request->files->get('attachments');
        $data = json_decode($request->request->get('data'), true);

        $parts = explode('/', $data['contract']);

        $repoPengajuan = $pengajuanRepository->findTotalContract(substr($parts[0], 0, 4));
        /** @var RefLayanan $layanan */
        $layanan = $refLayananRepository->find((int) $parts[2]);
        $naraSumber = $narasumberRepository->find($data['user']['id']);
        $parts[3] = str_pad($repoPengajuan, 3, 0, STR_PAD_LEFT);


        $pengajuan = new Pengajuan();
        $pengajuan->setContract(implode('/', $parts));
        $pengajuan->setPengajuanProgress(
            (new PengajuanProgress())
                ->setStatus(PengajuanStatusEnum::DALAM_PROSES)
        );
        $pengajuan->setPtsp($layanan->getRefPtsp());
        $pengajuan->setLayanan($layanan);
        $pengajuan->setUser($naraSumber);

        foreach ($files as $file) {
            $pengajuan->addAttachment(
                (new PengajuanAttachment())
                    ->setFile($file)
            );
        };

        $pengajuanRepository->create($pengajuan);


        return new JsonResponse($pengajuan, 201);
    }

}