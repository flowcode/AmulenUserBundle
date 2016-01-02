<?php

namespace Flowcode\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
* User controller.
*/
class AdminLoginController extends Controller
{
    /**
    * Login to admin panel.
    *
    * @Route("/admin/login", name="flowcode_admin_user_login")
    * @Method("GET")
    * @Template()
    */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array(
            'last_username' => $lastUsername,
            'error'         => $error,
        );
    }

    /**
    * Login to admin panel.
    *
    * @Route("/admin/login/check", name="flowcode_admin_user_check")
    * @Method("POST")
    * @Template()
    */
    public function checkAction()
    {
    }
}
