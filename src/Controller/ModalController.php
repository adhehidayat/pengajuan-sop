<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PengajuanProgressHistory;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ModalController extends AbstractController
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    #[Route('/modal/{entity}/{id}')]
    public function index($entity, $id): Response
    {
        $repository = $this->managerRegistry
            ->getManagerForClass("App\\Entity\\${entity}")
            ->getRepository("App\\Entity\\${entity}")
            ->find($id)
        ;

        $history =  $this->managerRegistry
            ->getManagerForClass(PengajuanProgressHistory::class)
            ->getRepository(PengajuanProgressHistory::class)
            ->findBy(['pengajuan' => $repository->getId()])
        ;

        return $this->render("Modal/${entity}.html.twig", [
            'repository' => $repository,
            'history' => $history
        ]);
    }
}
