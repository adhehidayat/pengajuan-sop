<?php
declare(strict_types=1);
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PartialPaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Model\Layanan\DetailLayanan;
use App\Dto\Model\Layanan\Layanan;
use App\Dto\Model\Layanan\RequirementLayanan;
use App\Entity\RefJenisLayanan;
use App\Entity\RefLayanan;
use App\Entity\RefLayananInternal;
use App\Entity\RefPtsp;
use Doctrine\ORM\EntityManagerInterface;

final class LayananGroupProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $results = [];

        $jenisLayanan = $this->em->getRepository(RefJenisLayanan::class)->findAll();

        foreach ($jenisLayanan as $jLayanan) {
            $results[] = new Layanan(
                $jLayanan->getId(),
                $jLayanan->getKode(),
                $jLayanan->getTitle(),
                []
            );
        }

        $publics = $this->em->getRepository(RefPtsp::class)->findAll();

        $requirements = [];
        foreach ($publics as $public) {
            $details = [];

            $detail = $this->em->getRepository(RefLayanan::class)->findBy(['refPtsp' => $public->getId()]);
            foreach ($detail as $d) {
                $files = [];

                foreach ($d->getRefLayananAttachments()->toArray() as $file) {
                    $files[] = $file->getFiles();
                }


                $details[] = new DetailLayanan(
                    $d->getId(),
                    $d->getNama(),
                    $d->getPersyaratan(),
                    $files
                );
            }
            $requirements[] = new RequirementLayanan(
                $public->getId(),
                $public->getNama(),
                $public->getIcon() ?: "twemoji:mosque",
                $public->getColor() ?: '#0163aa',
                $public->getDescription() ?: 'Pencatatan dan Evaluasi Kinerja Pegawai',
                null,
                $details
            ) ;
        }

        $result = array_filter($results, fn($value) => $value->id === 1 );
        $result[0]->requirement= $requirements;

        $internals = $this->em->getRepository(RefLayananInternal::class)->findAll();
        $requirementInternal = [];
        foreach ($internals as $internal) {
            $requirementInternal[] = new RequirementLayanan(
                $internal->getId(),
                $internal->getTitle(),
                $internal->getIcon(),
                $internal->getColor(),
                $internal->getDescription(),
                $internal->getLink(),
                []
            ) ;
        }

        $result2 = array_filter($results, fn($value) => $value->id === 2 );
        $int = array_values($result2);
        $int[0]->requirement= $requirementInternal;

        return $results;
    }

}