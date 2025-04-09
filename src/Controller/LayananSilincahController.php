<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LayananSilincahController extends AbstractController{
    #[Route('/layanan/silincah', name: 'app_layanan_silincah')]
    public function index(): Response
    {
        return $this->render('layanan_silincah/index.html.twig', [
            'controller_name' => 'LayananSilincahController',
        ]);
    }
}
