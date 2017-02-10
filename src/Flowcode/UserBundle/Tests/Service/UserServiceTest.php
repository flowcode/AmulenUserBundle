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

    public function testActivateUserRegister_userOk_returnTrue()
    {
        $user = $this->userService->findByUsername('user2');
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());
        $activateUser = $this->userService->activateUserRegister($user->getId(), $user->getRegisterToken());
        $this->assertTrue($activateUser);
        $this->assertNUll($user->getRegisterToken());
        $this->assertEquals(UserStatus::ACTIVE, $user->getStatus());
    }

    public function testActivateUserRegister_withInvalidUser_returnFalse()
    {
        $user = $this->userService->findByUsername('user2');
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());
        $activateUser = $this->userService->activateUserRegister(9999, $user->getRegisterToken());
        $this->assertFalse($activateUser);
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());
    }

    public function testActivateUserRegister_withInvalidToken_returnFalse()
    {
        $user = $this->userService->findByUsername('user2');
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());
        $activateUser = $this->userService->activateUserRegister($user->getId(), 'sarasa');
        $this->assertFalse($activateUser);
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());
    }

    public function testGenerateForgotToken_userOk_generateToken()
    {
        $user = $this->userService->findByUsername('user');
        $this->assertEmpty($user->getForgotToken());
        $this->userService->generateForgotToken($user);
        $this->assertNotEmpty($user->getForgotToken());
    }

    public function testCheckForgot_userOk_returnTrue()
    {
        $user = $this->userService->findByUsername('user3');
        $this->assertNotNull($user->getForgotToken());

        $checkForgotUser = $this->userService->checkForgot($user->getId(), $user->getForgotToken());

        $this->assertTrue($checkForgotUser);
        $this->assertNotNull($user->getForgotToken());
    }

    public function testCheckForgot_withInvalidUser_returnFalse()
    {
        $user = $this->userService->findByUsername('user3');
        $this->assertNotNull($user->getForgotToken());

        $checkForgotUser = $this->userService->checkForgot(9999, $user->getForgotToken());

        $this->assertFalse($checkForgotUser);
        $this->assertNotNull($user->getForgotToken());
    }

    public function testCheckForgot_withInvalidToken_returnFalse()
    {
        $user = $this->userService->findByUsername('user3');
        $this->assertNotNull($user->getForgotToken());

        $checkForgotUser = $this->userService->checkForgot($user->getId(), 'sarasa');

        $this->assertFalse($checkForgotUser);
        $this->assertNotNull($user->getForgotToken());
    }

}
