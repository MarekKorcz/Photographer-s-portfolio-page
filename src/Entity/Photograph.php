<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photograph
 *
 * @ORM\Table(name="photograph")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotographRepository")
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
