<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function upload(UploadedFile $file, $dir)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($dir, $fileName);

        return $fileName;
    }

    public function uploadDefaultPicture($file, $dir)
    {
        $fileName = md5(uniqid()).substr($file, -4);

        copy($file, $dir.'/'.$fileName);

        return $fileName;
    }
}