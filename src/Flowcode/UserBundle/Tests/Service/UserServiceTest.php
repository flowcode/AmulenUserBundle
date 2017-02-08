<?php

namespace Flowcode\UserBundle\Tests\Service;

use Flowcode\UserBundle\Tests\BaseTestCase;
use Flowcode\UserBundle\Exception\ExistentUserException;
use Flowcode\UserBundle\Entity\UserStatus;

class UserServiceTest extends BaseTestCase
{
    private $userService;

    public function setUp()
    {
        parent::setUp();
        $this->userService = $this->getContainer()->get('flowcode.user');
    }

    public function testLoadUserByUsername_usernameOk_returnUser()
    {
        $user = $this->userService->loadUserByUsername("user");
        if ($user == null) {
            $this->fail('User is null');
        }

        $this->assertEquals("user", $user->getUsername());
        $this->assertEquals("user@user.com", $user->getEmail());
        $this->assertNotEquals("1234", $user->getPassword());
    }

    public function testLoadUserByUsername_usernameInvalid_returnNull()
    {
        $user = $this->userService->loadUserByUsername("juancho");
        $this->assertNull($user);
    }

    public function testLoadUserByUsername_userStatusNotActive_returnNull()
    {
        $user = $this->userService->loadUserByUsername("pedro");
        $this->assertNull($user);
    }

    public function testCreate_userOk_createUser()
    {
        $user = $this->userService->createNewUser();
        $user->setUsername("pepe");
        $user->setPlainPassword("1234");
        $user->setEmail("pepe@pepe.com");

        $userBefore = $this->userService->findByUsername("pepe");
        $this->assertNull($userBefore);

        $userAfter = $this->userService->create($user);
        $this->assertNotNull($userAfter);
        $this->assertEquals("pepe", $userAfter->getUsername());
        $this->assertEquals("pepe@pepe.com", $userAfter->getEmail());
        $this->assertNotEquals("1234", $userAfter->getPassword());
        $this->assertEquals(UserStatus::INACTIVE, $userAfter->getStatus());
    }

    public function testCreate_usernameExistent_throwException()
    {
        $user = $this->userService->createNewUser();
        $username = "user";
        $user->setUsername($username);
        $user->setPlainPassword("1234");
        $user->setEmail("user@user.com");

        $userBefore = $this->userService->findByUsername($username);
        $this->assertNotNull($userBefore);

        $this->setExpectedException(ExistentUserException::class);
        $this->userService->create($user);
    }

    public function testCreate_emailExistent_throwException()
    {
        $user = $this->userService->createNewUser();
        $username = "user";

        $user->setUsername($username);
        $user->setPlainPassword("1234");
        $user->setEmail("user@user.com");

        $userBefore = $this->userService->findByUsername($username);
        $this->assertNotNull($userBefore);

        $this->setExpectedException(ExistentUserException::class);
        $this->userService->create($user);
    }

    public function testGenerateRegisterToken_userOk_generateToken()
    {
        $user = $this->userService->findByUsername('user');
        $this->assertEmpty($user->getRegisterToken());
        $this->userService->generateRegisterToken($user);
        $this->assertNotEmpty($user->getRegisterToken());
    }
}
