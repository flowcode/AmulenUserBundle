<?php

namespace Flowcode\UserBundle\Controller;

use Amulen\UserBundle\Entity\User;
use Flowcode\UserBundle\Form\UserRegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Login controller.
 */
class SecurityController extends Controller
{
    /**
     * Login to admin panel.
     *
     * @Route("/login", name="amulen_user_login")
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
            'error' => $error,
        );
    }

    /**
     * Login to admin panel.
     *
     * @Route("/login/check", name="amulen_user_check")
     * @Method("POST")
     * @Template()
     */
    public function checkAction()
    {
    }

    /**
     * Check.
     *
     * @Route("/forgot", name="amulen_login_forgot")
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
     * Finds and displays a User entity.
     *
     * @Route("/logout", name="amulen_user_logout")
     * @Method("GET")
     * @Template()
     */
    public function logoutAction()
    {
        return array();
    }

    /**
     * Forget check.
     *
     * @Route("/forgot_check", name="amulen_forgot_check")
     * @Method("POST")
     * @Template("FlowcodeUserBundle:Security:forgot.html.twig")
     */
    public function forgotCheckAction(Request $request)
    {
        $error = null;
        $last_email = null;

        $userService = $this->get("flowcode.user");
        $user = $userService->loadUserByUsername($request->get("_username"));

        if ($user) {
            $userService->resetPasssword($user);
        } else {
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

    /**
     * Register new user.
     *
     * @Route("/register", name="amulen_user_register")
     * @Method("GET")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createRegisterForm($user);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createRegisterForm(User $user)
    {
        $form = $this->createForm(new UserRegisterType(), $user, array(
            'action' => $this->generateUrl('amulen_user_register_do'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Register'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/register", name="amulen_user_register_do")
     * @Method("POST")
     * @Template("FlowcodeUserBundle:Security:register.html.twig")
     */
    public function doRegisterAction(Request $request)
    {
        $user = new User();

        $form = $this->createRegisterForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {

            // FIXME: This have to be in the service.
            $user->setStatus(User::STATUS_ACTIVE);

            $userManager = $this->container->get('flowcode.user');
            $userManager->create($user);

            // //FIXME: firewall name in security.yml
            // $firewall = 'public';
            // $token = $userManager->getAuthToken($user, $user->getPlainPassword(), $firewall);
            // // Fire the login event
            // // Logging the user in above the way we do it doesn't do this automatically
            // $event = new InteractiveLoginEvent($request, $token);
            // $userManager->loginUser($event);

            return $this->redirect($this->generateUrl('homepage'));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
