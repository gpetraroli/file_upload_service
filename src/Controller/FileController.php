<?php

namespace App\Controller;

use App\Entity\Container;
use App\Entity\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/api/file', name: 'file_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $fileData = $request->files->get('file');

        if (!$fileData) {
            return $this->json(['message' => 'No file uploaded'], 400);
        }

        $container = $this->em->getRepository(Container::class)->findOneBy(['token' => $request->request->get('token')]);
        if (!$container) {
            return $this->json(['message' => 'Container not found'], 404);
        }

        $uploadedFile = new UploadedFile();
        $uploadedFile->setContainer($container);
        $uploadedFile->setFile($fileData);
        $uploadedFile->setFileName($fileData->getClientOriginalName());
        $uploadedFile->setFileSize($fileData->getSize());

        $this->em->persist($uploadedFile);
        $this->em->flush();

        return $this->json([
            'id' => $uploadedFile->getId(),
            'name' => $uploadedFile->getFileName(),
            'size' => $uploadedFile->getFileSize(),
        ]);
    }

}