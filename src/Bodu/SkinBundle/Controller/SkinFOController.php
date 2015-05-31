<?php

namespace Bodu\SkinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SkinFOController extends Controller
{
    public function displayAction()
    {
        $request = $this->getRequest();

        $skinToUse = $this->getDoctrine()->getManager()->getRepository('BoduSkinBundle:Skin')->findBy(array('activ' => 1));
        //var_dump($skinToUse);

        if (count($skinToUse) != 1)
            return new Response('<!-- No skin to use -->');
        else {

            $skinUrl = $this->container->get('templating.helper.assets')->getUrl($skinToUse[0]->getCssFile());
            return new Response('<link rel="stylesheet" type="text/css" href="' . $skinUrl . '" />');
        }
    }
}
