<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Project;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;

class ProjectPictureUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof Project) {
            return;
        }

        $file = $entity->getMainPicture();

        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file, $this->uploader->getProjectPictureDir());
            $entity->setMainPicture($fileName);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Project) {
            return;
        }

        if ($fileName = $entity->getMainPicture()) {
            $entity->setMainPicture(new File($this->uploader->getProjectPictureDir().'/'.$fileName));
        }
    }

}