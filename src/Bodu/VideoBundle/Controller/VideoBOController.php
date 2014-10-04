<?php

namespace Bodu\VideoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bodu\VideoBundle\Entity\Video;
use Bodu\VideoBundle\Form\VideoType;

class VideoBOController extends Controller
{
    public function listVideoAction()
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        // If user has submit form => save new video order (if necessary)
        if ($request->getMethod() == 'POST')
        {
            try
            {
                // Get field with new video order
                $newOrderedList = $request->request->get('ordered-video-list');

                if ($newOrderedList != null)
                {
                    $newOrderedList = explode('&', str_replace('video-list[]=', '', $newOrderedList));

                    // Browse each video and update rank if necessary
                    for ($i = 0; $i < count($newOrderedList); $i++)
                    {
                        $videoToUpdate = $entityManager->getRepository('BoduVideoBundle:Video')->find($newOrderedList[$i]);
                        if ($videoToUpdate->getRank() != ($i + 1))
                        {
                            $videoToUpdate->setRank($i + 1);
                            $entityManager->persist($videoToUpdate);
                        }
                    }
                }

                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('bo-log-message', 'Classement des vidéos OK');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors du classement des vidéos');
            }
        }

        $orderedVideoList = $entityManager->getRepository('BoduVideoBundle:Video')->findBy(array(), array('rank' => 'asc'));

        return $this->render('BoduVideoBundle:BO:list.html.twig', array('videoList' => $orderedVideoList));
    }

    public function editVideoAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $video = ($id > 0) ? $entityManager->getRepository('BoduVideoBundle:Video')->find($id) : new Video;

        // If user has submit form => save video
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new VideoType, $video);
                $form->bind($request);

                if ($form->isValid())
                {
                    // For new video => set rank
                    if ($video->getId() == null || $video->getId() == 0)
                        $video->setRank($entityManager->getRepository('BoduVideoBundle:Video')->getMaxRank() + 1);

                    $entityManager->persist($video);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde de la vidéo OK');

                    return $this->redirect($this->generateUrl('bodu_video_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde de la vidéo');
            }
        }
        else
            $form = $this->createForm(new VideoType, $video);

        return $this->render('BoduVideoBundle:BO:edit.html.twig', array('videoToEdit' => $form->createView()));
    }

    public function deleteVideoAction($id)
    {
        if ($id > 0)
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $videoToDelete = $entityManager->getRepository('BoduVideoBundle:Video')->find($id);

                // If video found => delete it
                if ($videoToDelete != null)
                {
                    $entityManager->remove($videoToDelete);
                    $entityManager->flush();
                    $this->getRequest()->getSession()->getFlashBag()->add('bo-log-message', 'Suppression de la vidéo OK');
                }
            }
            catch (Exception $e)
            {
                $this->getRequest()->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression de la vidéo');
            }
        }

        // Return to video list
        return $this->redirect($this->generateUrl('bodu_video_bo_list'));
    }
}
