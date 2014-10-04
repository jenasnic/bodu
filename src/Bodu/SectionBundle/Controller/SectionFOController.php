<?php

namespace Bodu\SectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bodu\SectionBundle\Entity\Section;

class SectionFOController extends Controller
{
    public function displayAction($id)
    {
        $section = $this->getDoctrine()->getManager()->getRepository('BoduSectionBundle:Section')->find($id);
        return $this->render('BoduSectionBundle:FO:section.html.twig', array('section' => $section));
    }
}
