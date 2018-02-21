<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserServices
{
    private $fs;
    private $uploader;
    private $profilePictureDir;
    private $passwordEncoder;

    public function __construct(Filesystem $fs, FileUploader $uploader, UserPasswordEncoderInterface $passwordEncoder, $profilePictureDir)
    {
        $this->fs = $fs;
        $this->uploader = $uploader;
        $this->passwordEncoder = $passwordEncoder;
        $this->profilePictureDir = $profilePictureDir;
    }

    public function prePersistRegister(User $user){
        return $user
            ->setRoles($this->getUserRole())
            ->setPassword($this->encodePassword($user))
            ->setPicture($this->uploadPicture($user))
        ;
    }

    public function prePersistForgottenPassword(User $user){
        return $user
            ->setToken($this->getNewToken())
            ;
    }

    public function prePersistReinitialization(User $user, User $formUser){
        return $user
            ->setPassword($this->encodePassword($user->setPassword($formUser->getPassword())))
            ->setToken(null)
            ;
    }

    public function preLoadAccount(User $user){
        return $user
            ->setPicture($this->pictureStringToFile($user->getPicture()))
            ;
    }

    public function prePersistAccount(User $user, User $formUser){
        return $formUser
            ->setPassword($this->encodePassword($formUser) ?? $user->getPassword())
            ->setPicture($this->uploadPicture($formUser) ?? $user->getPicture())
            ->setEmail($formUser->getEmail() ?? $user->getEmail())
            ->setFirstname($formUser->getFirstname() ?? $user->getFirstname())
            ->setLastname($formUser->getLastname() ?? $user->getLastname())
        ;
    }

    public function preDeleteUser(User $user){
        $this->deletePicture($user->getPicture());
        return $user;
    }

    public function prePersistNewByAdmin(User $user){
        return $user
            ->setPassword($this->encodePassword($user))
            ->setPicture($this->uploadPicture($user))
            ;
    }

    public function preLoadByAdmin(User $user){
        return $user
            ->setPicture($this->pictureStringToFile($user->getPicture()))
            ;
    }

    public function prePersistEditByAdmin(User $user, User $formUser){
        return $formUser
            ->setPicture($this->uploadPicture($formUser) ?? $user->getPicture())
            ->setEmail($formUser->getEmail() ?? $user->getEmail())
            ->setFirstname($formUser->getFirstname() ?? $user->getFirstname())
            ->setLastname($formUser->getLastname() ?? $user->getLastname())
            ;
    }

    private function getUserRole()
    {
        return 'ROLE_USER';
    }

    private function getNewToken()
    {
        return hash("sha512", uniqid());
    }

    private function encodePassword(User $user){
        if ($user->getPassword()){
            return $this->passwordEncoder->encodePassword($user, $user->getPassword());
        }
        return null;
    }

    private function uploadPicture(User $user){
        if ($user->getPicture()) {
            return $this->uploader->upload($user->getPicture(), $this->profilePictureDir);
        }
        return null;
    }

    private function pictureStringToFile($picture){
        if ($picture != null && gettype($picture) == 'string'){
            return new File($this->profilePictureDir.'/'.$picture);
        }
        return null;
    }

    private function deletePicture($picture){
        if ($picture != null && gettype($picture) == 'string'){
            $this->fs->remove($this->profilePictureDir.'/'.$picture);
        }
        return null;
    }
}