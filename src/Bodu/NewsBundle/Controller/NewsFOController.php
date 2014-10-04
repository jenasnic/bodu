<?php

namespace Bodu\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsFOController extends Controller
{
    public function displayAction($id)
    {
        $newsList = array();
        $entityManager = $this->getDoctrine()->getManager();

        // If news'id is specified => display specified news
        // NOTE : news must be published
        if ($id > 0)
            $newsList = $entityManager->getRepository('BoduNewsBundle:News')->findBy(array('id' => $id, 'published' => true));
        // Else display all published news
        else
            $newsList = $entityManager->getRepository('BoduNewsBundle:News')->findBy(array('published' => true), array('rank' => 'asc'));

        return $this->render('BoduNewsBundle:FO:news.html.twig', array('newsList' => $newsList));
    }
}
