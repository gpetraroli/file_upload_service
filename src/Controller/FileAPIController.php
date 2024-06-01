<?php

namespace App\Controller;

use App\Entity\Container;
use App\Entity\UploadedFile;
use App\service\TemporaryFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Attribute\Route;

class FileAPIController extends AbstractController
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

        $container = $this->em->getRepository(Container::class)->findOneBy(['token' => $request->headers->get('Token')]);

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
            'createdAt' => $uploadedFile->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/api/file/{name}', name: 'file_get', methods: ['GET'])]
    public function get(Request $request, string $name, TemporaryFileManager $temporaryFileManager): JsonResponse
    {
        $container = $this->em->getRepository(Container::class)->findOneBy(['token' => $request->headers->get('Token')]);

        if (!$container) {
            return $this->json(['message' => 'Container not found'], 404);
        }

        $uploadedFile = $this->em->getRepository(UploadedFile::class)->findOneBy(['container' => $container, 'fileName' => $name]);

        if (!$uploadedFile) {
            return $this->json(['message' => 'File not found'], 404);
        }

        $file = new File($this->getParameter('kernel.project_dir') . '/data/uploads/' . $uploadedFile->getFileName());

        if ($container->getType() === Container::TYPE_PUBLIC) {
            $url = $temporaryFileManager->generateTemporaryFile($file);

            return $this->json([
                'name' => $uploadedFile->getFileName(),
                'size' => $uploadedFile->getFileSize(),
                'url' => $url,
            ]);
        }

        return $this->json([
            'name' => $uploadedFile->getFileName(),
            'size' => $uploadedFile->getFileSize(),
            'base64' => base64_encode(file_get_contents($file->getPathname())),
        ]);
    }
}