<?php

namespace Bodu\SlideshowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;

use Bodu\SectionBundle\Entity\Section;
use Bodu\SlideshowBundle\Entity\Picture;
use Bodu\SlideshowBundle\Entity\Slideshow;
use Bodu\SlideshowBundle\Form\SlideshowType;

class SlideshowBOController extends Controller
{
    public function listSlideshowAction()
    {
        $sectionList = $this->getDoctrine()->getManager()->getRepository('BoduSectionBundle:Section')->findAll();

        $request = $this->getRequest();
        $sectionName = $request->get('slideshow-name');
        $sectionId = $request->get('slideshow-section');

        $slideshowList = $this->getDoctrine()->getManager()->getRepository('BoduSlideshowBundle:Slideshow')->searchSlideshow($sectionName, $sectionId);

        return $this->render('BoduSlideshowBundle:BO:listSlideshow.html.twig', array(
                'sectionList' => $sectionList,
                'slideshowList' => $slideshowList));
    }

    public function editSlideshowAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $slideshow = ($id > 0) ? $entityManager->getRepository('BoduSlideshowBundle:Slideshow')->find($id) : new Slideshow;

        // If user has submit form => save slideshow
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new SlideshowType, $slideshow);
                $form->bind($request);

                // If border is activated => thickness and color must be set (color in hexa)
                if ($slideshow->getActivBorder() && ($slideshow->getBorderColor() == null || $slideshow->getThicknessValue() == null
                        || !preg_match('/[0-9a-fA-F]{6}/', $slideshow->getBorderColor())))
                {
                    $form->get('activBorder')->addError(new FormError("La bordure est activée : vous devez indiquer la couleur (code héxa 6 car.) et l'épaisseur"));
                    if (!preg_match('/[0-9a-fA-F]{6}/', $slideshow->getBorderColor()))
                        $form->get('borderColor')->addError(new FormError("Code non valide"));
                    if ($slideshow->getThicknessValue() == null)
                        $form->get('thicknessValue')->addError(new FormError("Epaisseur non valide"));
                }

                if ($form->isValid())
                {
                    // For new slideshow => set rank
                    if ($slideshow->getId() == null || $slideshow->getId() == 0)
                        $slideshow->setRank($entityManager->getRepository('BoduSlideshowBundle:Slideshow')->getMaxRank() + 1);
                    // For existing slideshow => get picture order and update rank if necessary
                    else
                    {
                        // Get field with new picture order
                        $newOrderedList = $request->request->get('ordered-picture-list');

                        if ($newOrderedList != null)
                        {
                            $newOrderedList = explode('&', str_replace('picture-list[]=', '', $newOrderedList));

                            // Browse each picture and update rank if necessary
                            for ($i = 0; $i < count($newOrderedList); $i++)
                            {
                                $pictureToUpdate = $entityManager->getRepository('BoduSlideshowBundle:Picture')->find($newOrderedList[$i]);

                                if ($pictureToUpdate->getRank() != ($i + 1))
                                {
                                    $pictureToUpdate->setRank($i + 1);
                                    $entityManager->persist($pictureToUpdate);
                                }
                            }

                            $request->getSession()->getFlashBag()->add('bo-log-message', 'Classement des images OK');
                        }
                    }

                    $entityManager->persist($slideshow);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde du diaporama OK');

                    return $this->redirect($this->generateUrl('bodu_slideshow_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde du diaporama');
            }
        }
        else
            $form = $this->createForm(new SlideshowType, $slideshow);

        return $this->render('BoduSlideshowBundle:BO:editSlideshow.html.twig', array('slideshowToEdit' => $form->createView()));
    }

    public function deleteSlideshowAction($id)
    {
        if ($id > 0)
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $slideshowToDelete = $entityManager->getRepository('BoduSlideshowBundle:Slideshow')->find($id);

                // If slideshow found => delete it
                if ($slideshowToDelete != null)
                {
                    $folderToDelete = $this->container->getParameter('kernel.root_dir') . '/../web/userfiles/slideshow/slideshow_' . $id;

                    // NOTE : All pictures linked to slideshow will be removed
                    $entityManager->remove($slideshowToDelete);
                    $entityManager->flush();

                    // Delete slideshow folder with all pictures
                    if (file_exists($folderToDelete))
                    {
                        $fileSystem = new Filesystem();
                        $fileSystem->remove($folderToDelete);
                    }

                    $this->getRequest()->getSession()->getFlashBag()->add('bo-log-message', 'Suppression du diaporama OK');
                }
            }
            catch (Exception $e)
            {
                $this->getRequest()->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression du diaporama');
            }
        }

        // Return to slideshow list
        return $this->redirect($this->generateUrl('bodu_slideshow_bo_list'));
    }
}
