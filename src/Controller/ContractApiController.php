<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Pengajuan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ContractApiController extends AbstractController
{
    #[Route('/api/contract', name: 'app_contractapi_getcontract', methods: "GET")]
    public function getContract(EntityManagerInterface $entity, Request $request): Response
    {
        $contract = $request->get('contract');
        $_token = $request->getSession()->get('_csrf/Pengajuan');

        if (!$this->isCsrfTokenValid('Pengajuan', $_token)) {
            throw new AccessDeniedHttpException('Invalid Token');
        }

        $repository = $entity->getRepository(Pengajuan::class)->findTotalContractByTahun();

        return $this->json($repository);
    }
}
