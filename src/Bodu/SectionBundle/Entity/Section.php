<?php

namespace Bodu\SectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section
 *
 * @ORM\Table(name="section")
 * @ORM\Entity(repositoryClass="Bodu\SectionBundle\Entity\SectionRepository")
 */
class Section
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection $slideshows
     *
     * @ORM\OneToMany(targetEntity="Bodu\SlideshowBundle\Entity\Slideshow", mappedBy="section")
     * @ORM\OrderBy({"rank" = "ASC"})
     */
    private $slideshows;


    public function __construct()
    {
        $this->slideshows = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

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
     * @return Section
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
     * @return Section
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
     * Add slideshow to section
     *
     * @param Bodu\SlideshowBundle\Entity\Slideshow $slideshow
     * @return Section
     */
    public function addSlideshow(\Bodu\SlideshowBundle\Entity\Slideshow $slideshow)
    {
        $this->slideshows[] = $slideshow;
        return $this;
    }

    /**
     * Remove slideshow from section
     *
     * @param Bodu\SlideshowBundle\Entity\Slideshow $slideshow
     */
    public function removeSlideshow(\Bodu\SlideshowBundle\Entity\Slideshow $slideshow)
    {
        $this->slideshows->removeElement($slideshow);
    }

    /**
     * Get slideshow list
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getSlideshows()
    {
        return $this->slideshows;
    }
}
