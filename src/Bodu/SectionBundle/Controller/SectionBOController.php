<?php

namespace Bodu\SectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bodu\SlideshowBundle\Entity\Slideshow;
use Bodu\SectionBundle\Entity\Section;
use Bodu\SectionBundle\Form\SectionType;

class SectionBOController extends Controller
{
    public function listSectionAction()
    {
        $orderedSectionList = $this->getDoctrine()->getManager()->getRepository('BoduSectionBundle:Section')->findBy(array(), array('name' => 'asc'));
        return $this->render('BoduSectionBundle:BO:list.html.twig', array('sectionList' => $orderedSectionList));
    }

    public function editSectionAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $section = ($id > 0) ? $entityManager->getRepository('BoduSectionBundle:Section')->find($id) : new Section;

        // If user has submit form => save section
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new SectionType, $section);
                $form->bind($request);

                if ($form->isValid())
                {
                    // For existing section => get slideshow order and update rank if necessary
                    if ($section->getId() == null || $section->getId() > 0)
                    {
                        // Get field with new slideshow order
                        $newOrderedList = $request->request->get('ordered-slideshow-list');

                        if ($newOrderedList != null)
                        {
                            $newOrderedList = explode('&', str_replace('slideshow-list[]=', '', $newOrderedList));

                            // Browse each slideshow and update rank if necessary
                            for ($i = 0; $i < count($newOrderedList); $i++)
                            {
                                $slideshowToUpdate = $entityManager->getRepository('BoduSlideshowBundle:Slideshow')->find($newOrderedList[$i]);
                                if ($slideshowToUpdate->getRank() != ($i + 1))
                                {
                                    $slideshowToUpdate->setRank($i + 1);
                                    $entityManager->persist($slideshowToUpdate);
                                }
                            }
                        }
                    }

                    $entityManager->persist($section);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde de la page OK');

                    return $this->redirect($this->generateUrl('bodu_section_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde de la page');
            }
        }
        else
            $form = $this->createForm(new SectionType, $section);

        return $this->render('BoduSectionBundle:BO:edit.html.twig', array('sectionToEdit' => $form->createView()));
    }

    public function deleteSectionAction($id)
    {
        if ($id > 0)
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $sectionToDelete = $entityManager->getRepository('BoduSectionBundle:Section')->find($id);

                // If section found => delete it
                if ($sectionToDelete != null)
                {
                    // Update all slideshow linked to section
                    foreach ($sectionToDelete->getSlideshows() as $slideshowToUpdate)
                    {
                        $slideshowToUpdate->setSection(null);
                        $entityManager->persist($slideshowToUpdate);
                    }

                    $entityManager->remove($sectionToDelete);
                    $entityManager->flush();

                    $this->getRequest()->getSession()->getFlashBag()->add('bo-log-message', 'Suppression de la page OK');
                }
            }
            catch (Exception $e)
            {
                $this->getRequest()->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression de la page');
            }
        }

        // Return to section list
        return $this->redirect($this->generateUrl('bodu_section_bo_list'));
    }
}
