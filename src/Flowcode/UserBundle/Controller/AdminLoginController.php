<?php

namespace Flowcode\UserBundle\Controller;

/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class AdminLoginController extends Controller
{
    /**
     * Login to admin panel.
     *
     * @Route("/login", name="flowcode_admin_user_login")
     * @Method("GET")
     * @Template()
     */
    public function login()
    {
    }

    /**
     * Login to admin panel.
     *
     * @Route("/login", name="flowcode_admin_user_check")
     * @Method("GET")
     * @Template()
     */
    public function check()
    {
    }
}
