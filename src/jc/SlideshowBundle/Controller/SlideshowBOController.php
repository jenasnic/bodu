<?php

namespace jc\SlideshowBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use jc\SlideshowBundle\Entity\Slideshow;
use jc\SlideshowBundle\Form\SlideshowType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SlideshowBOController extends Controller {

    /**
     * @Route("/admin/slideshow/list", name="jc_slideshow_bo_list")
     */
    public function listSlideshowAction() {

        $slideshowList = $this->getDoctrine()->getManager()->getRepository('jcSlideshowBundle:Slideshow')->findBy(array(), array('rank' => 'asc'));
        return $this->render('jcSlideshowBundle:BO:listSlideshow.html.twig', array('slideshowList' => $slideshowList));
    }

    /**
     * @Route("/admin/slideshow/create", name="jc_slideshow_bo_create")
     */
    public function createSlideshowAction(Request $request) {

        try {

            $name = $request->request->get('name');

            // Create new slideshow with specified name
            $entityManager = $this->getDoctrine()->getManager();
            $slideshowToCreate = new Slideshow();
            $slideshowToCreate->setName($name);
            $slideshowToCreate->setPublished(true);
            $slideshowToCreate->setActivBorder(false);
            $slideshowToCreate->setRank($entityManager->getRepository('jcSlideshowBundle:Slideshow')->getMaxRank() + 1);

            $entityManager->persist($slideshowToCreate);
            $entityManager->flush();

            // Get id for new slideshow and build URL to edit it
            $id = $slideshowToCreate->getId();
            $redirectUrl = $this->generateUrl('jc_slideshow_bo_edit', array('id' => $id));

            return new JsonResponse(array('success' => true, 'redirectUrl' => $redirectUrl, 'id' => $id));
        }
        catch (Exception $e) {
            return new JsonResponse(array('success' => false, 'message' => 'Erreur lors de la création'));
        }
    }

    /**
     * @Route("/admin/slideshow/edit/{id}", defaults={"id" = 0}, name="jc_slideshow_bo_edit")
     */
    public function editSlideshowAction(Request $request, $id) {

        $entityManager = $this->getDoctrine()->getManager();

        $slideshow = $entityManager->getRepository('jcSlideshowBundle:Slideshow')->find($id);

        // If user has submit form => save slideshow
        if ($request->getMethod() == 'POST') {

            try {

                $form = $this->createForm(new SlideshowType(), $slideshow);
                $form->handleRequest($request);

                if ($form->isValid()) {

                    // Process pictures
                    $this->processPictures($request, $slideshow);

                    $entityManager->persist($slideshow);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde OK');

                    return $this->redirect($this->generateUrl('jc_slideshow_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e) {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde');
            }
        }
        else
            $form = $this->createForm(new SlideshowType(), $slideshow);

        return $this->render('jcSlideshowBundle:BO:editSlideshow.html.twig', array('slideshowToEdit' => $form->createView()));
    }

    /**
     * @Route("/admin/slideshow/delete/{id}", requirements={"id" = "\d+"}, name="jc_slideshow_bo_delete")
     */
    public function deleteSlideshowAction(Request $request, $id) {

        if ($id > 0) {

            try {

                $entityManager = $this->getDoctrine()->getManager();
                $slideshowToDelete = $entityManager->getRepository('jcSlideshowBundle:Slideshow')->find($id);

                // If slideshow found => delete it
                if ($slideshowToDelete != null) {

                    $relativeFolderPath = $this->getParameter('jc_slideshow.root_path') . '/slideshow_' . $id;
                    $folderToDelete = $this->getParameter('kernel.root_dir') . '/../web' . $relativeFolderPath;

                    // NOTE : All pictures linked to slideshow will be removed
                    $entityManager->remove($slideshowToDelete);
                    $entityManager->flush();

                    // Delete slideshow folder with all pictures
                    if (file_exists($folderToDelete)) {

                        $fileSystem = new Filesystem();
                        $fileSystem->remove($folderToDelete);
                    }

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Suppression OK');
                }
            }
            catch (Exception $e) {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression');
            }
        }

        // Return to slideshow list
        return $this->redirect($this->generateUrl('jc_slideshow_bo_list'));
    }

    /**
     * Allows to process slideshow's pictures to update name or/and rank.
     * @param Slideshow $slideshow slideshow we want to update pictures name/rank
     */
    private function processPictures(Request $request, $slideshow) {

        foreach ($slideshow->getPictures() as $pictureToUpdate) {

            $pictureId = $pictureToUpdate->getId();

            $name = $request->request->get('picture-name-' . $pictureId);
            $rank = $request->request->get('picture-rank-' . $pictureId);

            $pictureToUpdate->setName($name);
            $pictureToUpdate->setRank($rank);
        }
    }
}
