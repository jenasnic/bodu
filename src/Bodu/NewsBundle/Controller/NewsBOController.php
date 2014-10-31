<?php

namespace Bodu\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;

use Bodu\NewsBundle\Entity\News;
use Bodu\NewsBundle\Form\NewsType;

class NewsBOController extends Controller
{
    public function listNewsAction()
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        // If user has submit form => save new news order (if necessary)
        if ($request->getMethod() == 'POST')
        {
            try
            {
                // Get field with new menu order
                $newOrderedList = $request->request->get('ordered-news-list');
        
                if ($newOrderedList != null)
                {
                    $newOrderedList = explode('&', str_replace('news-list[]=', '', $newOrderedList));
        
                    // Browse each news and update rank if necessary
                    for ($i = 0; $i < count($newOrderedList); $i++)
                    {
                        $newsToUpdate = $entityManager->getRepository('BoduNewsBundle:News')->find($newOrderedList[$i]);
                        if ($newsToUpdate->getRank() != ($i + 1))
                        {
                            $newsToUpdate->setRank($i + 1);
                            $entityManager->persist($newsToUpdate);
                        }
                    }
                }

                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('bo-log-message', 'Classement des actus OK');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors du classement des actus');
            }
        }

        $orderedNewsList = $entityManager->getRepository('BoduNewsBundle:News')->findBy(array(), array('rank' => 'asc'));

        return $this->render('BoduNewsBundle:BO:list.html.twig', array('newsList' => $orderedNewsList));
    }

    public function editNewsAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $news = ($id > 0) ? $entityManager->getRepository('BoduNewsBundle:News')->find($id) : new News;

        // If user has submit form => save news
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new NewsType, $news);
                $form->bind($request);

                // If no picture already loaded nor uploaded picture => add error
                if ($news->getPictureUrl() == null && $news->getPictureFile() == null)
                    $form->get('pictureFile')->addError(new FormError("Aucune image n'a encore été chargée : vous devez charger une image"));

                if ($form->isValid())
                {
                    // Process upload
                    $this->processUpload($news);

                    // For new news => set rank
                    if ($news->getId() == null || $news->getId() == 0)
                        $news->setRank($entityManager->getRepository('BoduNewsBundle:News')->getMaxRank() + 1);

                    $entityManager->persist($news);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde de l\'actu OK');

                    return $this->redirect($this->generateUrl('bodu_news_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde de l\'actu');
            }
        }
        else
            $form = $this->createForm(new NewsType, $news);

        return $this->render('BoduNewsBundle:BO:edit.html.twig', array('newsToEdit' => $form->createView()));
    }

    public function deleteNewsAction($id)
    {
        if ($id > 0)
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $newsToDelete = $entityManager->getRepository('BoduNewsBundle:News')->find($id);

                // If news found => delete it
                if ($newsToDelete != null)
                {
                    $fileToDelete = $this->container->getParameter('kernel.root_dir') . '/../web' . $newsToDelete->getPictureUrl();

                    $entityManager->remove($newsToDelete);
                    $entityManager->flush();

                    // Delete banner picture file if exist
                    if (file_exists($fileToDelete) && is_file($fileToDelete))
                        unlink($fileToDelete);

                    $this->getRequest()->getSession()->getFlashBag()->add('bo-log-message', 'Suppression de l\'actu OK');
                }
            }
            catch (Exception $e)
            {
                $this->getRequest()->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression de l\'actu');
            }
        }

        // Return to news list
        return $this->redirect($this->generateUrl('bodu_news_bo_list'));
    }

    /**
     * Allows to upload file defined in News object.
     * @param News $news
     */
    private function processUpload($news)
    {
        // If picture is defined => process upload
        if (null !== $news->getPictureFile())
        {
            $relativeFolderPath = '/userfiles/news';
            $absoluteFolderPath = $this->container->getParameter('kernel.root_dir') . '/../web' . $relativeFolderPath;

            // Remove old picture if exist
            $oldPictureToDelete = $this->container->getParameter('kernel.root_dir') . '/../web' . $news->getPictureUrl();
            if (file_exists($oldPictureToDelete) && is_file($oldPictureToDelete))
                unlink($oldPictureToDelete);

            $pictureName = $news->getPictureFile()->getClientOriginalName();

            // Move file in userfiles folder + save new URL
            $news->getPictureFile()->move($absoluteFolderPath, $pictureName);
            $news->setPictureUrl($relativeFolderPath . '/' . $pictureName);
        }
    }
}
