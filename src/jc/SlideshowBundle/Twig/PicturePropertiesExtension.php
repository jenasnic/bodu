<?php

namespace jc\SlideshowBundle\Twig;

class PicturePropertiesExtension extends \Twig_Extension {

    private $rootDir;

    public function __construct($rootDir) {
        $this->rootDir = $rootDir;
    }
    
    public function getName() {
        return 'picture_properties_twig_extension';
    }

    public function getFunctions() {
        return array(
                'pictureWidth' => new \Twig_Function_Method($this, 'getPictureWidth'),
                'pictureHeight' => new \Twig_Function_Method($this, 'getPictureHeight'));
    }

    /**
     * @param string $pictureUrl
     * @return width for specified picture URL.
     */
    public function getPictureWidth($pictureUrl) {
        return getimagesize($this->rootDir . '/../web' . $pictureUrl)[0];
    }

    /**
     * @param string $pictureUrl
     * @return height for specified picture URL.
     */
    public function getPictureHeight($pictureUrl) {
        return getimagesize($this->rootDir . '/../web' . $pictureUrl)[1];
    }
}
