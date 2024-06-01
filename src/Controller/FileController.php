<?php

namespace App\Controller;


use App\Entity\Container;
use App\Entity\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/file', name: 'app_file')]
class FileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/{id}', name: '_list')]
    public function list(Container $container, Discovery $discovery, Authorization $authorization, Request $request): Response
    {
        $uploadedFiles = $this->em->getRepository(UploadedFile::class)->findBy(
            ['container' => $container],
            ['createdAt' => 'DESC']
        );

        return $this->render('file/list.html.twig', [
            'uploadedFiles' => $uploadedFiles,
            'container' => $container,
        ]);
    }
}