<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Project;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ProjectListener
{
    private $tokenStorage;
    private $uploader;
    private $fs;

    public function __construct(TokenStorageInterface $tokenStorage, FileUploader $uploader, Filesystem $fs)
    {
        $this->tokenStorage = $tokenStorage;
        $this->uploader = $uploader;
        $this->fs = $fs;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Project) {
            return;
        }

        $entity->setCreator($this->tokenStorage->getToken()->getUser());
        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
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

    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        $this->deleteFile($entity);
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

    public function deleteFile($entity){

        if (!$entity instanceof Project) {
            return;
        }

        $this->fs->remove($entity->getMainPicture());
        foreach ($entity->getSubPictures() as $subPicture){
            $this->fs->remove($subPicture);
        }
    }
}