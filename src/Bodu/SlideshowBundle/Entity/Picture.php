<?php

namespace Bodu\SlideshowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity(repositoryClass="Bodu\SlideshowBundle\Entity\PictureRepository")
 */
class Picture
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
     * @ORM\Column(name="pictureUrl", type="text")
     */
    private $pictureUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnailUrl", type="text")
     */
    private $thumbnailUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vertical", type="boolean")
     */
    private $vertical;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var Bodu\SlideshowBundle\Entity\Slideshow
     *
     * @ORM\ManyToOne(targetEntity="Bodu\SlideshowBundle\Entity\Slideshow")
     * @ORM\JoinColumn(nullable=false)
     */
    private $slideshow;

    /**
     * Additional property used to upload file for main picture
     */
    private $pictureFile;

    /**
     * Additional property used to upload file for thumbnail  (i.e. small picture)
     */
    private $thumbnailFile;


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
     * @return Picture
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
     * Set pictureUrl
     *
     * @param string $pictureUrl
     * @return Picture
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    /**
     * Get pictureUrl
     *
     * @return string 
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * Set thumbnailUrl
     *
     * @param string $thumbnailUrl
     * @return Picture
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    /**
     * Get thumbnailUrl
     *
     * @return string 
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * Set vertical
     *
     * @param boolean $vertical
     * @return Picture
     */
    public function setVertical($vertical)
    {
        $this->vertical = $vertical;
    
        return $this;
    }
    
    /**
     * Get vertical
     *
     * @return boolean
     */
    public function getVertical()
    {
        return $this->vertical;
    }

    /**
     * Set rank
     *
     * @param string $rank
     * @return Picture
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
     * Set slideshow
     *
     * @param Bodu\SlideshowBundle\Entity\Slideshow $slideshow
     * @return Picture
     */
    public function setSlideshow($slideshow)
    {
        $this->slideshow = $slideshow;

        return $this;
    }

    /**
     * Get slideshow
     *
     * @return Bodu\SlideshowBundle\Entity\Slideshow 
     */
    public function getSlideshow()
    {
        return $this->slideshow;
    }

    /**
     * Set file
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $pictureFile
     * @return Picture
     */
    public function setPictureFile($pictureFile)
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }

    /**
     * Get picture file
     *
     * @return Symfony\Component\HttpFoundation\File\UploadedFile 
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * Set file
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $thumbnailFile
     * @return Picture
     */
    public function setThumbnailFile($thumbnailFile)
    {
        $this->thumbnailFile = $thumbnailFile;

        return $this;
    }

    /**
     * Get picture file
     *
     * @return Symfony\Component\HttpFoundation\File\UploadedFile 
     */
    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }
}
