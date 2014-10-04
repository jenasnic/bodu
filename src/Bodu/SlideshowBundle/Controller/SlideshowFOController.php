<?php

namespace Bodu\SlideshowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bodu\SlideshowBundle\Entity\Slideshow;

class SlideshowFOController extends Controller
{
    public function displayAction($id)
    {
        $slideshow = $this->getDoctrine()->getManager()->getRepository('BoduSlideshowBundle:Slideshow')->find($id);
        return $this->render('BoduSlideshowBundle:FO:slideshow.html.twig', array('slideshow' => $slideshow));
    }
}
