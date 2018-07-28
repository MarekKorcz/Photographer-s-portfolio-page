<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Photograph
 *
 * @ORM\Table(name="photograph")
 * @ORM\Entity(repositoryClass="App\Repository\PhotographRepository")
 * @Vich\Uploadable
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
    private $photo;

    /**
     * @Vich\UploadableField(mapping="photographs", fileNameProperty="photo")
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
    
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function getPhoto()
    {
        return $this->photo;
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
