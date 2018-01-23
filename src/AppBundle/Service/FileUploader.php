<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $userProfilePictureDir;

    public function __construct($userProfilePictureDir)
    {
        $this->userProfilePictureDir = $userProfilePictureDir;
    }

    public function upload(UploadedFile $file, $dir)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($dir, $fileName);

        return $fileName;
    }

    public function getUserProfilePictureDir()
    {
        return $this->userProfilePictureDir;
    }
}