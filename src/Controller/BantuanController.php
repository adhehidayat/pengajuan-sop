<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BantuanController extends AbstractController{
    #[Route('/bantuan', name: 'app_bantuan')]
    public function index(): Response
    {
        return $this->render('bantuan/index.html.twig', [
            'controller_name' => 'BantuanController',
        ]);
    }
}
