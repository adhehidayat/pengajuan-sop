<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatistikController extends AbstractController
{
    #[Route(path: "/api/statistik", name: "api_statistik", methods: ["GET"])]
    public function index(): JsonResponse
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
