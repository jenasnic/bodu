<?php

namespace Bodu\SlideshowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Slideshow
 *
 * @ORM\Table(name="slideshow")
 * @ORM\Entity(repositoryClass="Bodu\SlideshowBundle\Entity\SlideshowRepository")
 */
class Slideshow
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
     * @var boolean
     *
     * @ORM\Column(name="activBorder", type="boolean")
     */
    private $activBorder;

    /**
     * @var string
     *
     * @ORM\Column(name="borderColor", type="string", length=6, nullable=true)
     */
    private $borderColor;

    /**
     * @var integer
     *
     * @ORM\Column(name="thicknessValue", type="integer", nullable=true)
     */
    private $thicknessValue;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnailPosition", type="string", length=55)
     */
    private $thumbnailPosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var Bodu\SectionBundle\Entity\Section
     *
     * @ORM\ManyToOne(targetEntity="Bodu\SectionBundle\Entity\Section")
     * @ORM\JoinColumn(nullable=true)
     */
    private $section;

    /**
     * @var ArrayCollection $pictures
     *
     * @ORM\OneToMany(targetEntity="Picture", mappedBy="slideshow", cascade={"remove"})
     * @ORM\OrderBy({"rank" = "ASC"})
     */
    private $pictures;


    public function __construct()
    {
        $this->pictures = new ArrayCollection();
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
     * @return Slideshow
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
     * @return Slideshow
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
     * Set activBorder
     *
     * @param boolean $activBorder
     * @return News
     */
    public function setActivBorder($activBorder)
    {
        $this->activBorder = $activBorder;

        return $this;
    }

    /**
     * Get activBorder
     *
     * @return boolean
     */
    public function getActivBorder()
    {
        return $this->activBorder;
    }

    /**
     * Set borderColor
     *
     * @param string $borderColor
     * @return Slideshow
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    /**
     * Get borderColor
     *
     * @return string 
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * Set thicknessValue
     *
     * @param integer $thicknessValue
     * @return Slideshow
     */
    public function setThicknessValue($thicknessValue)
    {
        $this->thicknessValue = $thicknessValue;

        return $this;
    }

    /**
     * Get thicknessValue
     *
     * @return integer 
     */
    public function getThicknessValue()
    {
        return $this->thicknessValue;
    }

    /**
     * Set thumbnailPosition
     *
     * @param string $thumbnailPosition
     * @return Slideshow
     */
    public function setThumbnailPosition($thumbnailPosition)
    {
        $this->thumbnailPosition = $thumbnailPosition;

        return $this;
    }

    /**
     * Get thumbnailPosition
     *
     * @return string 
     */
    public function getThumbnailPosition()
    {
        return $this->thumbnailPosition;
    }

    /**
     * Set rank
     *
     * @param string $rank
     * @return Slideshow
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set section
     *
     * @param Bodu\SectionBundle\Entity\Section $section
     * @return Slideshow
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return Bodu\SectionBundle\Entity\Section 
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Add picture to slideshow
     *
     * @param Picture $picture
     * @return Slideshow
     */
    public function addPicture(Picture $picture)
    {
        $this->pictures[] = $picture;
        return $this;
    }

    /**
     * Remove picture from slideshow
     *
     * @param Picture $picture
     */
    public function removePicture(Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Get picture list
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getPictures()
    {
        return $this->pictures;
    }
}
