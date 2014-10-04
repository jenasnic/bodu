<?php

namespace Bodu\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuFOController extends Controller
{
    public function displayAction()
    {
        $request = $this->getRequest();

        $orderedMenuList = $this->getDoctrine()->getManager()->getRepository('BoduMenuBundle:Menu')->findBy(array(), array('rank' => 'asc'));
        return $this->render('BoduMenuBundle:FO:menu.html.twig', array(
                'menuList' => $orderedMenuList,
                'rootCurrentPath' => $request->get('rootCurrentPath'),
                'rootUri' => $request->get('rootUri')
        ));
    }
}
