<?php

namespace Flowcode\UserBundle\Tests\Service;

use Flowcode\UserBundle\Tests\BaseTestCase;

class UserServiceTest extends BaseTestCase
{
    private $userService;

    public function setUp()
    {
        parent::setUp();
        $this->userService = $this->getContainer()->get('flowcode.user');
    }

    public function testFindAll_getAllUsers()
    {
        $users = $this->userService->findAll();
        foreach ($users as $user) {
            echo $user->getUsername();
        }

        $this->assertEquals(1, sizeof($users));
    }

    public function testLoadUserByUsername_usernameOk_returnUser()
    {
        $user = $this->userService->loadUserByUsername("juan");
        if ($user == null) {
            $this->fail('User is null');
        }

        $this->assertEquals("juan", $user->getUsername());
        $this->assertEquals("juan@juan.com", $user->getEmail());
        $this->assertNotEquals("1234", $user->getPassword());
    }

    public function testLoadUserByUsername_usernameInvalid_returnNull()
    {
        $user = $this->userService->loadUserByUsername("juancho");
        $this->assertNull($user);
    }
}
