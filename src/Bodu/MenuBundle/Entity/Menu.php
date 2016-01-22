<?php

namespace Bodu\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Bodu\MenuBundle\Entity\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    private $evaluatedWeight;
    private $calculatedWidth;

    /**
     * Set id
     *
     * @param string $id
     * @return Menu
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
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
     * @return Menu
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
     * Set url
     *
     * @param string $url
     * @return Menu
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Menu
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
     * Set evaluated weight
     *
     * @param integer $evaluatedWeight
     * @return Menu
     */
    public function setEvaluatedWeight($evaluatedWeight)
    {
        $this->evaluatedWeight = $evaluatedWeight;

        return $this;
    }

    /**
     * Get evaluated weight
     *
     * @return integer 
     */
    public function getEvaluatedWeight()
    {
        return $this->evaluatedWeight;
    }

    /**
     * Set calculated width
     *
     * @param integer $calculatedWidth
     * @return Menu
     */
    public function setCalculatedWidth($calculatedWidth)
    {
        $this->calculatedWidth = $calculatedWidth;

        return $this;
    }

    /**
     * Get calculated width
     *
     * @return integer 
     */
    public function getCalculatedWidth()
    {
        return $this->calculatedWidth;
    }
}
