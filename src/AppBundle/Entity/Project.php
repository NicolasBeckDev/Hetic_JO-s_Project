<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{

    public function __construct() {
        $this->followers = new ArrayCollection();
        $this->participants = new ArrayCollection();
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="objectif", type="text")
     */
    private $objectif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="in_progress", type="boolean")
     */
    private $inProgress;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_validated", type="boolean", nullable=true)
     */
    private $isValidated;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="projects")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="District", inversedBy="projects")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     */
    private $district;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="followedProjects")
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="participatingProjects")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdProjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPicture", type="string", length=255)
     */
    private $mainPicture;

    /**
     * @var array
     *
    @ORM\Column(name="subPictures", type="array", nullable=true)
     */
    private $subPictures;

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
     * Set name
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Project
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set inProgress
     *
     * @param boolean $inProgress
     *
     * @return Project
     */
    public function setInProgress($inProgress)
    {
        $this->inProgress = $inProgress;

        return $this;
    }

    /**
     * Get inProgress
     *
     * @return bool
     */
    public function getInProgress()
    {
        return $this->inProgress;
    }

    /**
     * Set isValidated
     *
     * @param boolean $isValidated
     *
     * @return Project
     */
    public function setIsValidated($isValidated)
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * Get isValidated
     *
     * @return bool
     */
    public function getIsValidated()
    {
        return $this->isValidated;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Project
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add follower
     *
     * @param \AppBundle\Entity\User $follower
     *
     * @return Project
     */
    public function addFollower(\AppBundle\Entity\User $follower)
    {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \AppBundle\Entity\User $follower
     */
    public function removeFollower(\AppBundle\Entity\User $follower)
    {
        $this->followers->removeElement($follower);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Add participant
     *
     * @param \AppBundle\Entity\User $participant
     *
     * @return Project
     */
    public function addParticipant(\AppBundle\Entity\User $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * Remove participant
     *
     * @param \AppBundle\Entity\User $participant
     */
    public function removeParticipant(\AppBundle\Entity\User $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Set creator
     *
     * @param \AppBundle\Entity\User $creator
     *
     * @return Project
     */
    public function setCreator(\AppBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set district
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return Project
     */
    public function setDistrict(\AppBundle\Entity\District $district = null)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \AppBundle\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set objectif
     *
     * @param string $objectif
     *
     * @return Project
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectif
     *
     * @return string
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Project
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }


    /**
     * Set mainPicture
     *
     * @param string $mainPicture
     *
     * @return Project
     */
    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Get mainPicture
     *
     * @return string
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * Set subPictures
     *
     * @param array $subPictures
     *
     * @return Project
     */
    public function setSubPictures($subPictures)
    {
        $this->subPictures = $subPictures;

        return $this;
    }

    /**
     * Get subPictures
     *
     * @return array
     */
    public function getSubPictures()
    {
        return $this->subPictures;
    }
}
