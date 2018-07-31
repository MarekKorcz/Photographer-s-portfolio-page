<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Photograph
 *
 * @ORM\Table(name="photograph")
 * @ORM\Entity(repositoryClass="App\Repository\PhotographRepository")
 */
class Photograph
{
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=200)
     * @var string
     */
    private $photoName;
    
    /**
     * @var File
     */
    private $photoFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Session", inversedBy="photographs")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id", nullable=false)
     */
    private $session;


    
    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;
    }

    public function getPhotoName()
    {
        return $this->photoName;
    }
    
    // virtual method for displaying photos in easy admin's show and edit actions
    public function getPhotoPath() {
        
        // this path still not working (TO FIX)
        return './../public/uploads/photographs/' . $this->getPhotoName();
    }

        public function setPhotoFile(File $photo = null)
    {
        $this->photoFile = $photo;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($photo) {
            
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFile()
    {
        return $this->photoFile;
    }
    
    public function getUpdatedAt() {
        
        return $this->updatedAt;
    }

        /**
     * Set session to photograph
     * 
     * @param \App\Entity\Session $session
     * @return \App\Entity\Photograph
     */
    public function setSession(\App\Entity\Session $session = null) 
    {        
        $this->session = $session;
        
        return $this;
    }
    
    /**
     * Get session
     * 
     * @return \App\Entity\Session
     */
    public function getSession()
    {
        return $this->session;
    }
}
