<?php

namespace Bodu\HomeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Bodu\HomeBundle\Entity\Banner;
use Bodu\HomeBundle\Form\BannerType;

class HomeBOController extends Controller {

    /**
     * @Route("/admin", name="bodu_home_bo")
     */
    public function homeAction() {
        return $this->render('BoduHomeBundle:BO:home.html.twig');
    }

    /**
     * @Route("/admin/home/banner/list", name="bodu_home_bo_banner_list")
     */
    public function listBannerAction(Request $request) {

        $entityManager = $this->getDoctrine()->getManager();

        // If user has submit form => save new banner order (if necessary)
        if ($request->getMethod() == 'POST') {

            try {

                // Get all existing banner
                $bannerList = $entityManager->getRepository('BoduHomeBundle:Banner')->findAll();

                $test = 'DEBUG ';
                // Browse banner and get new rank/title using id...
                foreach ($bannerList as $bannerToUpdate) {

                    $bannerId = $bannerToUpdate->getId();

                    $title = $request->request->get('banner-title-' . $bannerId);
                    $rank = $request->request->get('banner-rank-' . $bannerId);

                    $bannerToUpdate->setTitle($title);
                    $bannerToUpdate->setRank($rank);
                    $entityManager->persist($bannerToUpdate);
                }

                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('bo-log-message', 'Mise à jour des bannières OK');
            }
            catch (Exception $e) {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la mise à jour des bannières');
            }
        }

        $orderedBannerList = $entityManager->getRepository('BoduHomeBundle:Banner')->findBy(array(), array('rank' => 'asc'));

        return $this->render('BoduHomeBundle:BO:listBanner.html.twig', array('bannerList' => $orderedBannerList));
    }

    /**
     * @Route("/admin/home/banner/upload", name="bodu_home_bo_banner_upload")
     */
    public function uploadPictureAction(Request $request) {

        $entityManager = $this->getDoctrine()->getManager();

        try {

            // Create new banner
            $banner = new Banner();

            // Set rank
            $banner->setRank($entityManager->getRepository('BoduHomeBundle:Banner')->getMaxRank() + 1);

            // Process file upload
            $file = $request->files->get('file');

            $relativeFolderPath = '/userfiles/home';
            $absoluteFolderPath = $this->getParameter('kernel.root_dir') . '/../web' . $relativeFolderPath;

            // For picture's name => use file name without extension
            $originalName = $file->getClientOriginalName();
            $dotIndex = strrpos($originalName, '.');
            $name = substr($originalName, 0, $dotIndex);
            $extension = substr($originalName, $dotIndex + 1);

            // For picture's file => use unique name and move file into appropriate folder
            $uniqueFileName = $this->getUniqueFileNameForBanner($absoluteFolderPath, $extension);
            $file->move($absoluteFolderPath, $uniqueFileName);

            $banner->setTitle($name);
            $banner->setPictureUrl($relativeFolderPath . '/' . $uniqueFileName);

            // Save new banner
            $entityManager->persist($banner);
            $entityManager->flush();

            return new JsonResponse(array('success' => true, 'id' => $banner->getId(), 'title' => $banner->getTitle(),
                            'rank' => $banner->getRank(), 'url' => $banner->getPictureUrl()));
        }
        catch (Exception $e) {
            return new JsonResponse(array('success' => false, 'message' => 'Erreur lors de la création de l\'image'));
        }
    }

    /**
     * @Route("/admin/home/banner/delete/{id}", requirements={"id" = "\d+"}, name="bodu_home_bo_banner_delete")
     */
    public function deleteBannerAction(Request $request, $id) {

        if ($id > 0) {

            try {

                $entityManager = $this->getDoctrine()->getManager();
                $bannerToDelete = $entityManager->getRepository('BoduHomeBundle:Banner')->find($id);

                // If banner found => delete it
                if ($bannerToDelete != null) {

                    $fileToDelete = $this->getParameter('kernel.root_dir') . '/../web' . $bannerToDelete->getPictureUrl();

                    $entityManager->remove($bannerToDelete);
                    $entityManager->flush();

                    // Delete banner picture file if exist
                    if (file_exists($fileToDelete) && is_file($fileToDelete))
                        unlink($fileToDelete);

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Suppression de la bannière OK');
                }
            }
            catch (Exception $e) {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression de la bannière');
            }
        }

        // Return to banner list
        return $this->redirect($this->generateUrl('bodu_home_bo_banner_list'));
    }

    private function getUniqueFileNameForBanner($bannerPath, $extension) {

        $i = 1;
        do {
            $fileName = 'banner_' . sprintf("%'.03d", $i++) . '.' . $extension;
        }
        while (file_exists($bannerPath . '/' . $fileName));

        return $fileName;
    }
}
