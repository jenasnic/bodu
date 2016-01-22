<?php

namespace Bodu\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuFOController extends Controller
{
    public function displayAction()
    {
        $request = $this->getRequest();

        $orderedMenuList = $this->getDoctrine()->getManager()->getRepository('BoduMenuBundle:Menu')->findBy(array(), array('rank' => 'asc'));

        $this->calculMenuWidthV1($orderedMenuList);

        return $this->render('BoduMenuBundle:FO:menu.html.twig', array(
                'menuList' => $orderedMenuList,
                'rootCurrentPath' => $request->get('rootCurrentPath'),
                'rootUri' => $request->get('rootUri')
        ));
    }

    private function calculMenuWidthV1($orderedMenuList) {

        // Count total characters used in menu
        $totalCharacterCount = 0;
        foreach ($orderedMenuList as $menu)
            $totalCharacterCount += strlen(utf8_decode($menu->getName()));

        if ($totalCharacterCount > 0) {

            // NOTE : Each element define padding/margin = 35px
            $totalSize = 960 - (35 * count($orderedMenuList));

            // Browse all menus items and calculate size to use
            foreach ($orderedMenuList as $menu) {

                $menuWidth = intval((strlen(utf8_decode($menu->getName())) * $totalSize / $totalCharacterCount));
                if ($menuWidth > 0)
                    $menu->setCalculatedWidth($menuWidth);
            }
        }
    }

    private function calculMenuWidthV2($orderedMenuList) {
    
        // Count total weight for menu items
        $totalWeight = 0;
        foreach ($orderedMenuList as $menu) {

            $menuWeight = $this->evalWeightWord($menu->getName());
            $menu->setEvaluatedWeight($menuWeight);
            $totalWeight += $menuWeight;
            //echo 'menu ' . $menu->getName() . ' => ' . $menuWeight . '</br>';
        }

        // NOTE : Each element define padding/margin = 35px
        $totalSize = 960 - (35 * count($orderedMenuList));

        // Browse all menus items and calculate size to use
        foreach ($orderedMenuList as $menu) {

            $menuWidth = intval($menu->getEvaluatedWeight() * $totalSize / $totalWeight);
            if ($menuWidth > 0)
                $menu->setCalculatedWidth($menuWidth);
        }
    }

    private function evalWeightWord($label) {

        $labelToProcess = utf8_decode($label);
        $weight = 0;

        for ($i = 0; $i < strlen($labelToProcess); $i++) {

            $charToCheck = $labelToProcess[$i];
            $asciiCode = ord($charToCheck);

            // For 'ijlI' => use 2
            if ($asciiCode == 105 || $asciiCode == 106 || $asciiCode == 108 || $asciiCode == 73)
                $weight += 2;
            // For 'frt1-' => use 4
            else if ($asciiCode == 102 || $asciiCode == 114 || $asciiCode == 116 || $asciiCode == 49)
                $weight += 4;
            // For 'mwMW' => use 10
            else if ($asciiCode == 109 || $asciiCode == 119 || $asciiCode == 77 || $asciiCode == 87)
                $weight += 10;
            // For number case => use 6
            else if ($asciiCode >= 48 && $asciiCode <= 57)
                $weight += 6;
            // For upper case => use 7
            else if ($asciiCode >= 65 && $asciiCode <= 90)
                $weight += 7;
            // For lower case and other => use 5 per default
            else
                $weight += 5;
        }

        return $weight;
    }
}
