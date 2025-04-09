<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class LayananController extends AbstractController
{
   /**
 * @Route("/api/statistik-layanan", name="api_statistik_layanan", methods={"GET"})
 */
public function getStatistikLayanan(): JsonResponse
    {
        $data = [
            'Penerima Layanan' => 220,
            'Terlayani' => 180,
            'Dalam Proses' => 25,
            'Layanan TMS' => 15
    ];

    return new JsonResponse($data);
    }

}
