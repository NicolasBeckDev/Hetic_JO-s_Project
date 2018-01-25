<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $userProfilePictureDir;
    private $projectPictureDir;

    public function __construct($userProfilePictureDir, $projectPictureDir)
    {
        $this->userProfilePictureDir = $userProfilePictureDir;
        $this->projectPictureDir = $projectPictureDir;
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

    public function getProjectPictureDir()
    {
        return $this->projectPictureDir;
    }
}