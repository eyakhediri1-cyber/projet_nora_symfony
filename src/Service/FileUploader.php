<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {}

    public function upload(UploadedFile $file): string
    {
        $safeFilename = $this->slugger->slug(
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
        );
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($this->targetDirectory, $fileName);
        return $fileName;
    }

    public function remove(string $fileName): void
    {
        $path = $this->targetDirectory.'/'.$fileName;
        if (file_exists($path)) unlink($path);
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}