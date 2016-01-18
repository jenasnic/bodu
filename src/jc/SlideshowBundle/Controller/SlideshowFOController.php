<?php

namespace jc\SlideshowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use jc\SlideshowBundle\Entity\Slideshow;

class SlideshowFOController extends Controller {

    public function displayAction($id) {

        $slideshow = $this->getDoctrine()->getManager()->getRepository('jcSlideshowBundle:Slideshow')->find($id);
        return $this->render('jcSlideshowBundle:FO:slideshow.html.twig', array('slideshow' => $slideshow));
    }
}
