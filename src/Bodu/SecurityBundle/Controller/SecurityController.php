<?php

namespace Bodu\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class SecurityController extends Controller
{
    public function loginAction()
    {
        // If user already logged => back to BO home
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->redirect($this->generateUrl('bodu_home_bo'));

        return $this->render('BoduSecurityBundle::login.html.twig');
    }

    private function getEncodedPassword($password, $algorithm)
    {
        $encoder = new MessageDigestPasswordEncoder($algorithm);
        return $encoder->encodePassword($password, null);
    }
}
