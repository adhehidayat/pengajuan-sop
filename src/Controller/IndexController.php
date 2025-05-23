<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route(path: '/', name: 'app_frontend')]
    function index(Request $request)
    {
//        return $this->render('front_end/index.html.twig', []);
        return $this->redirect('/admin');
    }
}
