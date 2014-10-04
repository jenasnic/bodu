<?php

namespace Bodu\SkinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skin
 *
 * @ORM\Table(name="skin")
 * @ORM\Entity(repositoryClass="Bodu\SkinBundle\Entity\SkinRepository")
 */
class Skin
{
    /**
     * @var integer
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
     * @ORM\Column(name="cssFile", type="string", length=255)
     */
    private $cssFile;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Skin
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
     * Set cssFile
     *
     * @param string $cssFile
     * @return Skin
     */
    public function setCssFile($cssFile)
    {
        $this->cssFile = $cssFile;

        return $this;
    }

    /**
     * Get cssFile
     *
     * @return string 
     */
    public function getCssFile()
    {
        return $this->cssFile;
    }
}
