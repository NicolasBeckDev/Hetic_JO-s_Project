<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserListener
{
    private $uploader;
    private $fs;
    private $passwordEncoder;

    public function __construct(FileUploader $uploader, Filesystem $fs, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->uploader = $uploader;
        $this->fs = $fs;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
        $this->UpdateRoles($entity);

        if(isset($entityChangeSet['password'])){
            $this->EncodePassword($entity);
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     * @throws \Exception
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        $entityChangeSet = $args->getEntityChangeSet();

        $this->updateRoles($entity);
        $this->uploadFile($entity);

        if(isset($entityChangeSet['password'])){
            $this->EncodePassword($entity);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {

    }

    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        $this->deleteFile($entity);
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof User) {
            return;
        }

        $picture = $entity->getPicture();

        if ($picture instanceof UploadedFile) {
            $pictureName = $this->uploader->upload($picture, $this->uploader->getUserProfilePictureDir());
            $entity->setPicture($pictureName);
        }
    }

    public function deleteFile($entity){

        if (!$entity instanceof User) {
            return;
        }

        $this->fs->remove($entity->getPicture());
    }

    public function encodePassword($entity){

        if (!$entity instanceof User) {
            return;
        }
        $entity->setPassword($this->passwordEncoder->encodePassword($entity, $entity->getPassword()));

    }

    public function updateRoles($entity){

        if (!$entity instanceof User) {
            return;
        }
        if(is_string($entity->getRoles())){
            $roles = explode(';', $entity->getRoles());
            $entity->setRoles($roles);
        }

    }
}