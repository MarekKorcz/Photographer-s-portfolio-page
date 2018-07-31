<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $photo)
    {
        $photoName = md5(uniqid()).'.'.$photo->guessExtension();

        $photo->move($this->getTargetDirectory(), $photoName);

        return $photoName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}