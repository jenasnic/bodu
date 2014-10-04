<?php

namespace Bodu\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;

use Bodu\HomeBundle\Entity\Banner;
use Bodu\HomeBundle\Form\BannerType;

class HomeBOController extends Controller
{
    public function homeAction()
    {
        return $this->render('BoduHomeBundle:BO:home.html.twig');
    }

    public function listBannerAction()
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        // If user has submit form => save new banner order (if necessary)
        if ($request->getMethod() == 'POST')
        {
            try
            {
                // Get field with new menu order
                $newOrderedList = $request->request->get('ordered-banner-list');

                if ($newOrderedList != null)
                {
                    $newOrderedList = explode('&', str_replace('banner-list[]=', '', $newOrderedList));

                    // Browse each banner and update rank if necessary
                    for ($i = 0; $i < count($newOrderedList); $i++)
                    {
                        $bannerToUpdate = $entityManager->getRepository('BoduHomeBundle:Banner')->find($newOrderedList[$i]);
                        if ($bannerToUpdate->getRank() != ($i + 1))
                        {
                            $bannerToUpdate->setRank($i + 1);
                            $entityManager->persist($bannerToUpdate);
                        }
                    }
                }

                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('bo-log-message', 'Classement des bannières OK');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors du classement des bannières');
            }
        }

        $orderedBannerList = $entityManager->getRepository('BoduHomeBundle:Banner')->findBy(array(), array('rank' => 'asc'));

        return $this->render('BoduHomeBundle:BO:listBanner.html.twig', array('bannerList' => $orderedBannerList));
    }

    public function editBannerAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $banner = ($id > 0) ? $entityManager->getRepository('BoduHomeBundle:Banner')->find($id) : new Banner;

        // If user has submit form => save banner
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new BannerType, $banner);
                $form->bind($request);

                // If no picture already loaded nor uploaded picture => add error
                if ($banner->getPictureUrl() == null && $banner->getPictureFile() == null)
                    $form->get('pictureFile')->addError(new FormError("Aucune image n'a encore été chargée : vous devez charger une image"));

                if ($form->isValid())
                {
                    // For new banner => set rank
                    if ($banner->getId() == null || $banner->getId() == 0)
                        $banner->setRank($entityManager->getRepository('BoduHomeBundle:Banner')->getMaxRank() + 1);

                    // Process upload
                    $this->processUpload($banner);

                    $entityManager->persist($banner);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde de la bannière OK');

                    return $this->redirect($this->generateUrl('bodu_home_bo_banner_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde de la bannière');
            }
        }
        else
            $form = $this->createForm(new BannerType, $banner);

        return $this->render('BoduHomeBundle:BO:editBanner.html.twig', array('bannerToEdit' => $form->createView()));
    }

    public function deleteBannerAction($id)
    {
        if ($id > 0)
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $bannerToDelete = $entityManager->getRepository('BoduHomeBundle:Banner')->find($id);

                // If banner found => delete it
                if ($bannerToDelete != null)
                {
                    $fileToDelete = $this->container->getParameter('kernel.root_dir') . '/../web' . $bannerToDelete->getPictureUrl();

                    $entityManager->remove($bannerToDelete);
                    $entityManager->flush();

                    // Delete banner picture file if exist
                    if (file_exists($fileToDelete) && is_file($fileToDelete))
                        unlink($fileToDelete);

                    $this->getRequest()->getSession()->getFlashBag()->add('bo-log-message', 'Suppression de la bannière OK');
                }
            }
            catch (Exception $e)
            {
                $this->getRequest()->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression de la bannière');
            }
        }

        // Return to banner list
        return $this->redirect($this->generateUrl('bodu_home_bo_banner_list'));
    }

    /**
     * Allows to upload file defined in Banner object.
     * @param Banner $banner
     */
    private function processUpload($banner)
    {
        // If picture is defined => process upload
        if (null !== $banner->getPictureFile())
        {
            $relativeFolderPath = '/userfiles/home';
            $absoluteFolderPath = $this->container->getParameter('kernel.root_dir') . '/../web' . $relativeFolderPath;

            // Remove old picture if exist
            $oldPictureToDelete = $this->container->getParameter('kernel.root_dir') . '/../web' . $banner->getPictureUrl();
            if (file_exists($oldPictureToDelete) && is_file($oldPictureToDelete))
                unlink($oldPictureToDelete);

            $pictureName = $banner->getPictureFile()->getClientOriginalName();

            // Move file in userfiles folder + save new URL
            $banner->getPictureFile()->move($absoluteFolderPath, $pictureName);
            $banner->setPictureUrl($relativeFolderPath . '/' . $pictureName);
        }
    }
}
