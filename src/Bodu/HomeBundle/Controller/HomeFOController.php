<?php

namespace Bodu\HomeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeFOController extends Controller {

    /**
     * @Route("/", name="bodu_home_fo")
     */
    public function homeAction() {

        $orderedBannerList = $this->getDoctrine()->getManager()->getRepository('BoduHomeBundle:Banner')->findBy(array(), array('rank' => 'asc'));
        return $this->render('BoduHomeBundle:FO:home.html.twig', array('bannerList' => $orderedBannerList));
    }
}
