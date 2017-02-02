<?php

namespace Flowcode\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Version;

/**
 * @Version("v1")
 */
class SecurityController extends FOSRestController
{
    /**
     * @Post("/login", name="api_security_login",  options={ "method_prefix" = false })
     */
    public function loginAction()
    {
        return 'login v1';
    }
}
