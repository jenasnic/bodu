<?php

namespace Bodu\VideoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoFOController extends Controller
{
    public function displayAction()
    {
        $videoList = $this->getDoctrine()->getManager()->getRepository('BoduVideoBundle:Video')->findBy(array('published' => true), array('rank' => 'asc'));
        return $this->render('BoduVideoBundle:FO:video.html.twig', array('videoList' => $videoList));
    }
}
