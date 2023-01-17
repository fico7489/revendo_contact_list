<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): ?string
    {
        $clientOriginalName = $file->getClientOriginalName();

        $file->move($this->getTargetDirectory(), $clientOriginalName);

        return $clientOriginalName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
