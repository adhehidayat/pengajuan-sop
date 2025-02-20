<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\RefLayanan;
use App\Util\SerializerUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PersyaratanApiController extends AbstractController
{
    #[Route('/api/persyaratan', name: 'api_get_persyaratan')]
    public function getPersyaratan(Request $request, EntityManagerInterface $entity, SerializerInterface $serializer): Response
    {
        $layanan = (int) $request->get('layanan');
        $_token = $request->headers->get('auth_token');

        if (!$this->isCsrfTokenValid('Pengajuan', $_token)) {
            throw new AccessDeniedHttpException('Invalid Token');
        }

        $em = $entity->getRepository(RefLayanan::class)->find($layanan);

        $cleanText = str_replace(['<p>', '</p>', '<div>', '</div>'], '', $em->getPersyaratan());
        $em->setPersyaratan($cleanText);

        $serialize = $serializer->serialize($em, 'json', ['groups' => ['get_ref_layanan']]);


        return $this->json(json_decode($serialize));
    }
}
