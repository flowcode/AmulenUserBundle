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

    /**
    * Check.
    *
    * @Route("/admin/login/forgot", name="amulen_admin_login_forgot")
    * @Method("GET")
    * @Template()
    */
    public function forgotAction()
    {
        $error = null;
        $last_email = null;
        
        return array(
            "error" => $error,
            "last_email" => $last_email,
            );
    }

    /**
    * Forget check.
    *
    * @Route("/admin/login/forgot_check", name="amulen_admin_login_forgot_check")
    * @Method("POST")
    * @Template("FlowcodeUserBundle:AdminLogin:forgot.html.twig")
    */
    public function forgotCheckAction(Request $request)
    {
        $error = null;
        $last_email = null;

        $userService = $this->get("flowcode.user");
        $user = $userService->loadUserByUsername($request->get("_username"));

        if($user){
            $userService->resetPasssword($user);
        }else{
            $error = array(
                "messageKey" => "security.login.unknown_user",
                "messageData" => array(),
            );
        }

        
        return array(
            "error" => $error,
            "last_email" => $last_email,
            );
    }
}
