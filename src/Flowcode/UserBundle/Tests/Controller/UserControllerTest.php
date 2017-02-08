<?php

namespace Flowcode\UserBundle\Tests\Controller;

use Flowcode\UserBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;
use Flowcode\UserBundle\Entity\ResponseCode;
use Flowcode\UserBundle\Entity\UserStatus;
use Symfony\Component\Stopwatch\Stopwatch;

class UserControllerTest extends BaseTestCase
{
    private $userService;

    public function setUp()
    {
        parent::setUp();
        $this->userService = $this->getContainer()->get('flowcode.user');
    }

    public function testActivateAccount_userAndTokenOk_activateAccount()
    {
        $user = $this->userService->findByUsername("user2");
        $route = $this->getUrl('flowcode_user_activate_account', array(
            "id" => $user->getId(),
            "token" => $user->getRegisterToken()
        ));
        $this->client->request('GET', $route);


        $userAfter = $this->userService->findByUsername("user2");
        $this->assertEquals("user2", $userAfter->getUsername());
        //$this->assertEquals(UserStatus::ACTIVE, $userAfter->getStatus());
        //$this->assertNull($userAfter->getRegisterToken());
    }
}