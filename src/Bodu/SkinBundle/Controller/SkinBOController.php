<?php

namespace Bodu\SkinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bodu\SkinBundle\Entity\Skin;
use Bodu\SkinBundle\Form\SkinType;

class SkinBOController extends Controller
{
    public function listSkinAction()
    {
        $orderedSkinList = $this->getDoctrine()->getManager()->getRepository('BoduSkinBundle:Skin')->findBy(array(), array('name' => 'asc'));
        return $this->render('BoduSkinBundle:BO:list.html.twig', array('skinList' => $orderedSkinList));
    }

    public function defaultSkinAction()
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        // Disallow all skins per default
        $entityManager->getRepository('BoduSkinBundle:Skin')->disallowDefaultSkin();

        $id = $request->get('skin-id');
        // Set default skin if exist
        if ($id > 0) {

            $skin = $entityManager->getRepository('BoduSkinBundle:Skin')->find($id);
            $skin->setActiv(true);

            $entityManager->persist($skin);
            $entityManager->flush();
        }

        $request->getSession()->getFlashBag()->add('bo-log-message', 'Mise à jour du skin par défaut OK');
        return $this->redirect($this->generateUrl('bodu_skin_bo_list'));
    }

    public function editSkinAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $skin = ($id > 0) ? $entityManager->getRepository('BoduSkinBundle:Skin')->find($id) : new Skin();

        // If user has submit form => save skin
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new SkinType, $skin);
                $form->bind($request);

                if ($form->isValid())
                {
                    $entityManager->persist($skin);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde du skin OK');

                    return $this->redirect($this->generateUrl('bodu_skin_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde du skin');
            }
        }
        else
            $form = $this->createForm(new SkinType, $skin);

        return $this->render('BoduSkinBundle:BO:edit.html.twig', array('skinToEdit' => $form->createView()));
    }

    public function deleteSkinAction($id)
    {
        if ($id > 0)
        {
            $request = $this->getRequest();

            try
            {
                $entityManager = $this->getDoctrine()->getManager();

                $skinToDelete = $entityManager->getRepository('BoduSkinBundle:Skin')->find($id);

                // If skin found => delete it
                if ($skinToDelete != null)
                {
                    $entityManager->remove($skinToDelete);
                    $entityManager->flush();
                }

                $request->getSession()->getFlashBag()->add('bo-log-message', 'Suppression du skin OK');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression du skin');
            }
        }

        // Return to skin list
        return $this->redirect($this->generateUrl('bodu_skin_bo_list'));
    }
}
