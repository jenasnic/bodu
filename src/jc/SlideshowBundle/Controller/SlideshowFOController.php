<?php

namespace jc\SlideshowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use jc\SlideshowBundle\Entity\Slideshow;

class SlideshowFOController extends Controller {

    public function indexAction() {

        $slideshowList = $this->getDoctrine()->getManager()->getRepository('jcSlideshowBundle:Slideshow')->findBy(array(), array('rank' => 'asc'));
        return $this->render('jcSlideshowBundle:FO:index.html.twig', array('slideshowList' => $slideshowList));
    }

    public function displayAction($id) {

        $slideshow = $this->getDoctrine()->getManager()->getRepository('jcSlideshowBundle:Slideshow')->find($id);
        return $this->render('jcSlideshowBundle:FO:slideshow.html.twig', array('slideshow' => $slideshow));
    }
}
