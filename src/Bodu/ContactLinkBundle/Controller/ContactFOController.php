<?php

namespace Bodu\ContactLinkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactFOController extends Controller
{
    public function displayAction()
    {
        $linkList = array();
        $entityManager = $this->getDoctrine()->getManager();

        $linkList = $this->getDoctrine()->getManager()->getRepository('BoduContactLinkBundle:ContactLink')->findBy(array(), array('rank' => 'asc'));

        return $this->render('BoduContactLinkBundle:FO:contact.html.twig', array('linkList' => $linkList));
    }
}
