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

        $mainPicture = $entity->getMainPicture();
        $subPictures = $entity->getSubPictures();

        if ($mainPicture instanceof UploadedFile) {
            $mainPictureName = $this->uploader->upload($mainPicture, $this->uploader->getProjectPictureDir());
            $entity->setMainPicture($mainPictureName);
        }

        if ($subPictures != null){
            $subPicturesNames = [];
            foreach ($subPictures as $subPicture){
                if ($subPicture instanceof UploadedFile) {
                    $subPicturesNames[] = $this->uploader->upload($subPicture, $this->uploader->getProjectPictureDir());
                }
            }
            $entity->setSubPictures($subPicturesNames);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Project) {
            return;
        }

        if ($mainPictureName = $entity->getMainPicture()) {
            $entity->setMainPicture(new File($this->uploader->getProjectPictureDir().'/'.$mainPictureName));
        }

        if ($subPicturesNames = $entity->getSubPictures()){
            $files = [];
            foreach ($subPicturesNames as $subPictureName){
                $files[] = new File($this->uploader->getProjectPictureDir().'/'.$subPictureName);
            }
            $entity->setSubPictures($files);
        }
    }

}