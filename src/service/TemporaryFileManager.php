<?php

namespace App\service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class TemporaryFileManager
{
    public function __construct(
        private Filesystem            $filesystem,
        private ContainerBagInterface $params,
    )
    {
    }

    public function generateTemporaryFile(File $file): string
    {
        $this->filesystem->copy($file->getPathname(), $this->params->get('kernel.project_dir') . '/public/tmp/' . $file->getFilename());

        return '/tmp/' . $file->getFilename();
    }

    public function deleteOldFiles(): void
    {
        $files = glob($this->params->get('kernel.project_dir') . '/public/tmp/*');

        foreach ($files as $file) {
            // Delete files older than 10 minutes
            if (filemtime($file) < time() - 600) {
                $this->filesystem->remove($file);
            }
        }
    }
}