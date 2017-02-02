<?php

namespace Flowcode\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Version;

class SecurityController extends FOSRestController
{
    public function loginAction()
    {
        return 'login v1';
    }
}
