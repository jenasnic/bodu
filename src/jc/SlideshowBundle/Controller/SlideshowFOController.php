<?php

namespace jc\SlideshowBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use jc\SlideshowBundle\Entity\Slideshow;

class SlideshowFOController extends Controller {

    /**
     * @Route("/ajax/slideshow/{id}", name="jc_slideshow_fo", defaults={"id" = 0})
     */
    public function indexAction($id) {

        $slideshow = $this->getDoctrine()->getManager()->getRepository('jcSlideshowBundle:Slideshow')->find($id);
        return $this->render('jcSlideshowBundle:FO:slideshow.html.twig', array('slideshow' => $slideshow));
    }
}
