<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Session
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
    
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    /**
     * @ORM\OneToMany(targetEntity="Photograph", mappedBy="session", cascade={"remove"})
     */
    private $photographs;
    
    
    
    public function __construct() 
    {
        $this->isActive = true;
        $this->photographs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() 
    {
        return $this->getName();
    }

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
    
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    public function setActive(bool $bool) 
    {        
        $this->isActive = $bool;

        return $this;
    }
    
    public function isActive() 
    {        
        return $this->isActive;
    }
    
    /**
     * Add photograph to photographs collection
     * 
     * @param \App\Entity\Photograph $photograph
     * @return \App\Entity\Session
     */
    public function setPhotograph(\App\Entity\Photograph $photograph)
    {
        $photograph->setSession($this);
        $this->photographs[] = $photograph;
        
        return $this;
    }
    
    /**
     * Get photographs
     * 
     * @return \App\Entity\Photograph
     */
    public function getPhotographs()
    {        
        return $this->photographs;
    }
}
