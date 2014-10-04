<?php

namespace Bodu\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeFOController extends Controller
{
    public function homeAction()
    {
        $orderedBannerList = $this->getDoctrine()->getManager()->getRepository('BoduHomeBundle:Banner')->findBy(array(), array('rank' => 'asc'));
        return $this->render('BoduHomeBundle:FO:home.html.twig', array('bannerList' => $orderedBannerList));
    }
}
