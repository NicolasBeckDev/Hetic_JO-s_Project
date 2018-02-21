<?php

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProjectServices
{
    private $fs;
    private $uploader;
    private $tokenStorage;
    private $pictureDir;

    public function __construct(Filesystem $fs, FileUploader $uploader, TokenStorageInterface $tokenStorage, $pictureDir)
    {
        $this->fs = $fs;
        $this->uploader = $uploader;
        $this->tokenStorage = $tokenStorage;
        $this->pictureDir = $pictureDir;
    }

    public function prePersistNew(Project $project){
        return $project
            ->setDate($this->stringToDate($project->getDate()))
            ->setInProgress(false)
            ->setCreator($this->getUser())
            ->setMainPicture($this->uploadMainPicture($project))
            ->setSubPictures($this->uploadSubPictures($project))
            ;
    }

    public function updateFollowedProjects(Project $project){
        if (in_array($project, $this->getUser()->getFollowedProjects()->toArray())){
            $this->getUser()->removeFollowedProject($project);
        }else{
            $this->getUser()->addFollowedProject($project);
        }
    }

    public function updateParticipatingProjects(Project $project){
        if (in_array($project, $this->getUser()->getParticipatingProjects()->toArray())){
            $this->getUser()->removeParticipatingProject($project);
        }else{
            $this->getUser()->addParticipatingProject($project);
        }
    }

    public function preLoadEdit(Project $project){
        return $project
            ->setMainPicture($this->pictureStringToFile($project->getMainPicture()))
            ->setSubPictures($this->picturesStringToFile($project->getSubPictures()))
            ;
    }

    public function prePersistEdit(Project $project, Project $formProject){
        return $formProject
            ->setMainPicture($this->uploadMainPicture($formProject) ?? $project->getMainPicture())
            ->setSubPictures($this->uploadSubPictures($formProject) ?? $project->getSubPictures())
            ->setDate($this->stringToDate($formProject) ?? $project->getDate())
            ;
    }

    public function preDeleteProject(Project $project){
        $this->deletePicture($project->getMainPicture());
        $this->deletePictures($project->getSubPictures());
        return $project;
    }

    public function prePersistValidated(Project $project){
        return $project
            ->setIsValidated(true)
            ;
    }

    private function stringToDate($date){
        return new \DateTime(str_replace('/', '-',$date));
    }

    private function uploadMainPicture(Project $project){
        if ($project->getMainPicture()) {
            return $this->uploader->upload($project->getMainPicture(), $this->pictureDir);
        }
        return null;
    }

    private function uploadSubPictures(Project $project){
        if ($project->getSubPictures()) {
            return array_map(function ($subPicture){ return $this->uploader->upload($subPicture, $this->pictureDir);}, $project->getSubPictures());
        }
        return null;
    }

    private function getUser(){
        return $this->tokenStorage->getToken()->getUser();
    }

    private function pictureStringToFile($picture){
        if ($picture != null && gettype($picture) == 'string'){
            return new File($this->pictureDir.'/'.$picture);
        }
        return null;
    }

    private function picturesStringToFile($pictures){
        if ($pictures != null && gettype($pictures) == 'array'){
            return array_map(function ($picture){return $this->pictureStringToFile($picture);}, $pictures);
        }
        return null;
    }

    private function deletePictures($pictures){
        if ($pictures != null && gettype($pictures) == 'string'){
            array_map(function ($picture){$this->deletePicture($picture);}, $pictures);
        }
        return null;
    }
    private function deletePicture($picture){
        if ($picture != null && gettype($picture) == 'string'){
            $this->fs->remove($this->pictureDir.'/'.$picture);
        }
        return null;
    }
}