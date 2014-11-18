<?php

namespace Bodu\ContactLinkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;

use Bodu\ContactLinkBundle\Entity\ContactLink;
use Bodu\ContactLinkBundle\Form\ContactLinkType;

class ContactLinkBOController extends Controller
{
    public function listContactLinkAction()
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        // If user has submit form => save new links order (if necessary)
        if ($request->getMethod() == 'POST')
        {
            try
            {
                // Get field with new menu order
                $newOrderedList = $request->request->get('ordered-link-list');

                if ($newOrderedList != null)
                {
                    $newOrderedList = explode('&', str_replace('link-list[]=', '', $newOrderedList));

                    // Browse each link and update rank if necessary
                    for ($i = 0; $i < count($newOrderedList); $i++)
                    {
                        $contactLinkToUpdate = $entityManager->getRepository('BoduContactLinkBundle:ContactLink')->find($newOrderedList[$i]);
                        if ($contactLinkToUpdate->getRank() != ($i + 1))
                        {
                            $contactLinkToUpdate->setRank($i + 1);
                            $entityManager->persist($contactLinkToUpdate);
                        }
                    }
                }

                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('bo-log-message', 'Classement des liens OK');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors du classement des liens');
            }
        }

        $orderedContactLinkList = $entityManager->getRepository('BoduContactLinkBundle:ContactLink')->findBy(array(), array('rank' => 'asc'));

        return $this->render('BoduContactLinkBundle:BO:list.html.twig', array('linkList' => $orderedContactLinkList));
    }

    public function editContactLinkAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $contactLink = ($id > 0) ? $entityManager->getRepository('BoduContactLinkBundle:ContactLink')->find($id) : new ContactLink;

        // If user has submit form => save link
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new ContactLinkType, $contactLink);
                $form->bind($request);

                // If no picture already loaded nor uploaded picture => add error
                if ($contactLink->getPictureUrl() == null && $contactLink->getPictureFile() == null)
                    $form->get('pictureFile')->addError(new FormError("Aucune image n'a encore été chargée : vous devez charger une image"));

                if ($form->isValid())
                {
                    // Process upload
                    $this->processUpload($contactLink);

                    // For new link => set rank
                    if ($contactLink->getId() == null || $contactLink->getId() == 0)
                        $contactLink->setRank($entityManager->getRepository('BoduContactLinkBundle:ContactLink')->getMaxRank() + 1);

                    $entityManager->persist($contactLink);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde du lien OK');

                    return $this->redirect($this->generateUrl('bodu_contact_link_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde du lien');
            }
        }
        else
            $form = $this->createForm(new ContactLinkType, $contactLink);

        return $this->render('BoduContactLinkBundle:BO:edit.html.twig', array('linkToEdit' => $form->createView()));
    }

    public function deleteContactLinkAction($id)
    {
        if ($id > 0)
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $contactLinkToDelete = $entityManager->getRepository('BoduContactLinkBundle:ContactLink')->find($id);

                // If link found => delete it
                if ($contactLinkToDelete != null)
                {
                    $fileToDelete = $this->container->getParameter('kernel.root_dir') . '/../web' . $contactLinkToDelete->getPictureUrl();

                    $entityManager->remove($contactLinkToDelete);
                    $entityManager->flush();

                    // Delete banner picture file if exist
                    if (file_exists($fileToDelete) && is_file($fileToDelete))
                        unlink($fileToDelete);

                    $this->getRequest()->getSession()->getFlashBag()->add('bo-log-message', 'Suppression du lien OK');
                }
            }
            catch (Exception $e)
            {
                $this->getRequest()->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression du lien');
            }
        }

        // Return to link list
        return $this->redirect($this->generateUrl('bodu_contact_link_bo_list'));
    }

    /**
     * Allows to upload file defined in ContactLink object.
     * @param ContactLink $contactLink
     */
    private function processUpload($contactLink)
    {
        // If picture is defined => process upload
        if (null !== $contactLink->getPictureFile())
        {
            $relativeFolderPath = '/userfiles/contact';
            $absoluteFolderPath = $this->container->getParameter('kernel.root_dir') . '/../web' . $relativeFolderPath;

            // Remove old picture if exist
            $oldPictureToDelete = $this->container->getParameter('kernel.root_dir') . '/../web' . $contactLink->getPictureUrl();
            if (file_exists($oldPictureToDelete) && is_file($oldPictureToDelete))
                unlink($oldPictureToDelete);

            $pictureName = $contactLink->getPictureFile()->getClientOriginalName();

            // Move file in userfiles folder + save new URL
            $contactLink->getPictureFile()->move($absoluteFolderPath, $pictureName);
            $contactLink->setPictureUrl($relativeFolderPath . '/' . $pictureName);
        }
    }
}
