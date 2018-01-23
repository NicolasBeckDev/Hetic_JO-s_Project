<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;

class UserProfilePictureUploadListener
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
        if (!$entity instanceof User) {
            return;
        }

        $file = $entity->getProfilePicture();

        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file, $this->uploader->getUserProfilePictureDir());
            $entity->setProfilePicture($fileName);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        if ($fileName = $entity->getProfilePicture()) {
            $entity->setBrochure(new File($this->uploader->getUserProfilePictureDir().'/'.$fileName));
        }
    }

}