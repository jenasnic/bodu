<?php

namespace Bodu\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bodu\MenuBundle\Entity\Menu;
use Bodu\MenuBundle\Form\MenuType;

class MenuBOController extends Controller
{
    public function listMenuAction()
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        // If user has submit form => save new menu order and menu width (if necessary)
        if ($request->getMethod() == 'POST')
        {
            try
            {
                // Get field with new menu order
                $newOrderedList = $request->request->get('ordered-menu-list');

                if ($newOrderedList != null)
                {
                    $newOrderedList = explode('&', str_replace('menu-list[]=', '', $newOrderedList));

                    // Browse each menu and update rank if necessary
                    for ($i = 0; $i < count($newOrderedList); $i++)
                    {
                        $menuToUpdate = $entityManager->getRepository('BoduMenuBundle:Menu')->find($newOrderedList[$i]);
                        if ($menuToUpdate->getRank() != ($i + 1))
                        {
                            $menuToUpdate->setRank($i + 1);
                            $entityManager->persist($menuToUpdate);
                        }
                    }
                }

                // Get field with new width
                $newWidthList = $request->request->get('width-menu-list');

                if ($newWidthList != null)
                {
                    $newWidthList = explode(';', $newWidthList);

                    // Browse each menu and update width if necessary
                    foreach ($newWidthList as $newWidthItem)
                    {
                        // NOTE : Each width item are composed as follow : [ID]:[width]
                        $newWidthItem = explode(':', $newWidthItem);

                        $menuToUpdate = $entityManager->getRepository('BoduMenuBundle:Menu')->find($newWidthItem[0]);
                        if ($menuToUpdate->getWidth() != $newWidthItem[1])
                        {
                            $menuToUpdate->setWidth($newWidthItem[1]);
                            $entityManager->persist($menuToUpdate);
                        }
                    }
                }

                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('bo-log-message', 'Classement et redimensionnement des menus OK');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors du classement des menus');
            }
        }

        $orderedMenuList = $entityManager->getRepository('BoduMenuBundle:Menu')->findBy(array(), array('rank' => 'asc'));

        return $this->render('BoduMenuBundle:BO:list.html.twig', array('menuList' => $orderedMenuList));
    }

    public function editMenuAction($id)
    {
        $request = $this->getRequest();
        $entityManager = $this->getDoctrine()->getManager();

        $menu = ($id > 0) ? $entityManager->getRepository('BoduMenuBundle:Menu')->find($id) : new Menu;

        // If user has submit form => save menu
        if ($request->getMethod() == 'POST')
        {
            try
            {
                $form = $this->createForm(new MenuType, $menu);
                $form->bind($request);

                if ($form->isValid())
                {
                    // For new menu => set rank and default width
                    if ($menu->getId() == null || $menu->getId() == 0) {

                        $menu->setRank($entityManager->getRepository('BoduMenuBundle:Menu')->getMaxRank() + 1);
                        $menu->setWidth(110);
                    }

                    $entityManager->persist($menu);
                    $entityManager->flush();

                    $request->getSession()->getFlashBag()->add('bo-log-message', 'Sauvegarde du menu OK');

                    return $this->redirect($this->generateUrl('bodu_menu_bo_list'));
                }
                else
                    $request->getSession()->getFlashBag()->add('bo-warning-message', 'Certains champs ne sont pas remplis correctement');
            }
            catch (Exception $e)
            {
                $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la sauvegarde du menu');
            }
        }
        else
            $form = $this->createForm(new MenuType, $menu);

        return $this->render('BoduMenuBundle:BO:edit.html.twig', array('menuToEdit' => $form->createView()));
    }

    public function deleteMenuAction($id)
    {
        if ($id > 0)
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $menuToDelete = $entityManager->getRepository('BoduMenuBundle:Menu')->find($id);

                // If menu found => delete it
                if ($menuToDelete != null)
                {
                    $entityManager->remove($menuToDelete);
                    $entityManager->flush();
                    $this->getRequest()->getSession()->getFlashBag()->add('bo-log-message', 'Suppression du menu OK');
                }
            }
            catch (Exception $e)
            {
                $this->getRequest()->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la suppression du menu');
            }
        }

        // Return to menu list
        return $this->redirect($this->generateUrl('bodu_menu_bo_list'));
    }
}
