<?php

namespace Flowcode\UserBundle\Tests\Controller;

use Flowcode\UserBundle\Tests\BaseTestCase;
use Flowcode\UserBundle\Entity\UserStatus;

class SecurityControllerTest extends BaseTestCase
{
    private $userService;
    private $container;

    public function setUp()
    {
        parent::setUp();
        $this->userService = $this->getContainer()->get('flowcode.user');
        $this->container = $this->getContainer();
    }

    public function testActivateAccount_userAndTokenOk_redirectOk()
    {
        $user = $this->userService->findByUsername("user2");
        $route = $this->getUrl('flowcode_user_activate_account', array(
            "id" => $user->getId(),
            "token" => $user->getRegisterToken()
        ));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $redirectUrl = $response->headers->get('location');
        $frontLoginUrl = $this->container->getParameter("front_url_login") . "?register=success";
        $this->container->get('doctrine')->getManager()->refresh($user);
        $userAfter = $this->userService->findByUsername("user2");

        $this->assertEquals("user2", $userAfter->getUsername());
        $this->assertEquals(UserStatus::ACTIVE, $userAfter->getStatus());
        $this->assertNull($userAfter->getRegisterToken());
        $this->assertEquals($frontLoginUrl, $redirectUrl);
    }

    public function testActivateAccount_userAndTokenFail_redirectFail()
    {
        $user = $this->userService->findByUsername("user2");
        $route = $this->getUrl('flowcode_user_activate_account', array(
            "id" => $user->getId(),
            "token" => "sarasa"
        ));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $redirectUrl = $response->headers->get('location');
        $frontLoginUrl = $this->container->getParameter("front_url_login") . "?register=failure";
        $this->container->get('doctrine')->getManager()->refresh($user);
        $userAfter = $this->userService->findByUsername("user2");

        $this->assertEquals("user2", $userAfter->getUsername());
        $this->assertEquals(UserStatus::IN_REGISTER, $userAfter->getStatus());
        $this->assertNotNull($userAfter->getRegisterToken());
        $this->assertEquals($frontLoginUrl, $redirectUrl);
    }

    public function testForgotCheckApi_userAndTokenOk_redirectOk()
    {
        $user = $this->userService->findByUsername("user3");
        $route = $this->getUrl('flowcode_user_forgot_check', array(
            "id" => $user->getId(),
            "token" => $user->getForgotToken()
        ));

        $this->client->request('GET', $route);

        $response = $this->client->getResponse();
        $redirectUrl = $response->headers->get('location');
        $this->container->get('doctrine')->getManager()->refresh($user);
        $userAfter = $this->userService->findByUsername("user3");
        $frontRecoverUrl = $this->container->getParameter("front_url_recover") . "?recover=success";

        $this->assertEquals("user3", $userAfter->getUsername());
        $this->assertEquals(UserStatus::ACTIVE, $userAfter->getStatus());
        $this->assertNull($userAfter->getForgotToken());
        $this->assertEquals($frontRecoverUrl, $redirectUrl);
    }

    public function testForgotCheckApi_userAndTokenFail_redirectFail()
    {
        $user = $this->userService->findByUsername("user3");
        $route = $this->getUrl('flowcode_user_forgot_check', array(
            "id" => $user->getId(),
            "token" => "sarasa"
        ));

        $this->client->request('GET', $route);

        $response = $this->client->getResponse();
        $redirectUrl = $response->headers->get('location');
        $this->container->get('doctrine')->getManager()->refresh($user);
        $userAfter = $this->userService->findByUsername("user3");
        $frontRecoverUrl = $this->container->getParameter("front_url_recover") . "?recover=failure";

        $this->assertEquals("user3", $userAfter->getUsername());
        $this->assertEquals(UserStatus::ACTIVE, $userAfter->getStatus());
        $this->assertNotNull($userAfter->getForgotToken());
        $this->assertEquals($frontRecoverUrl, $redirectUrl);
    }
}
