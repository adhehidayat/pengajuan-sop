<?php

namespace App\Controller\Api;

use App\Components\Enum\PengajuanStatusEnum;
use App\Entity\Narasumber;
use App\Entity\Pengajuan;
use App\Entity\PengajuanAttachment;
use App\Entity\PengajuanProgress;
use App\Entity\RefLayanan;
use App\Repository\NarasumberRepository;
use App\Repository\PengajuanRepository;
use App\Repository\RefLayananRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PengajuanApiController extends AbstractController
{
    public function __construct(private DenormalizerInterface $denormalizer)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request, PengajuanRepository $pengajuanRepository, RefLayananRepository $refLayananRepository, NarasumberRepository $narasumberRepository): JsonResponse
    {
        $files = $request->files->get('attachments');
        $data = json_decode($request->request->get('data'), true);

        $docs = new ArrayCollection();



        $parts = explode('/', $data['contract']);

        $repoPengajuan = $pengajuanRepository->findTotalContract(substr($parts[0], 0, 4));
        /** @var RefLayanan $layanan */
        $layanan = $refLayananRepository->find((int) $parts[2]);
        $naraSumber = $narasumberRepository->find($data['user']['id']);
        $parts[3] = str_pad($repoPengajuan, 3, 0, STR_PAD_LEFT);


        /** @var Pengajuan $pengajuan */
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
        dump($pengajuan, $naraSumber);
        $pengajuanRepository->create($pengajuan);


        return new JsonResponse([
            'status' => 'ok',
            'attachments' => $docs,
            'data' => $data
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return $this->denormalizer->denormalize($data, $type);
    }
}