<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{

    public function __construct() {
        $this->followedProjects = new ArrayCollection();
        $this->participatingProjects = new ArrayCollection();
        $this->createdProjects = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(name="picture", type="string", nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="followers")
     * @ORM\JoinTable(name="users_projects_follow")
     */
    private $followedProjects;

    /**
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="participants")
     * @ORM\JoinTable(name="users_projects_participate")
     */
    private $participatingProjects;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="creator")
     */
    private $createdProjects;

    /**
     * @ORM\Column(type="array", nullable=false)
     *
     */
    private $roles;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set profilePicture
     *
     * @param $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Add followedProject
     *
     * @param \AppBundle\Entity\Project $followedProject
     *
     * @return User
     */
    public function addFollowedProject(Project $followedProject)
    {
        $this->followedProjects[] = $followedProject;

        return $this;
    }

    /**
     * Remove followedProject
     *
     * @param \AppBundle\Entity\Project $followedProject
     */
    public function removeFollowedProject(Project $followedProject)
    {
        $this->followedProjects->removeElement($followedProject);
    }

    /**
     * Get followedProjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowedProjects()
    {
        return $this->followedProjects;
    }

    /**
     * Add participatingProject
     *
     * @param \AppBundle\Entity\Project $participatingProject
     *
     * @return User
     */
    public function addParticipatingProject(Project $participatingProject)
    {
        $this->participatingProjects[] = $participatingProject;

        return $this;
    }

    /**
     * Remove participatingProject
     *
     * @param \AppBundle\Entity\Project $participatingProject
     */
    public function removeParticipatingProject(Project $participatingProject)
    {
        $this->participatingProjects->removeElement($participatingProject);
    }

    /**
     * Get participatingProject
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipatingProjects()
    {
        return $this->participatingProjects;
    }

    /**
     * Add createdProject
     *
     * @param \AppBundle\Entity\Project $createdProject
     *
     * @return User
     */
    public function addCreatedProject(Project $createdProject)
    {
        $this->createdProjects[] = $createdProject;

        return $this;
    }

    /**
     * Remove createdProject
     *
     * @param \AppBundle\Entity\Project $createdProject
     */
    public function removeCreatedProject(Project $createdProject)
    {
        $this->createdProjects->removeElement($createdProject);
    }

    /**
     * Get createdProjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatedProjects()
    {
        return $this->createdProjects;
    }

    /**
     * Set roles
     *
     * @param array The user roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return array The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized);
    }
}
