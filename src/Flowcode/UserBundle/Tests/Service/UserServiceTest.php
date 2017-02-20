<?php

namespace Flowcode\UserBundle\Tests\Service;

use Flowcode\UserBundle\Tests\BaseTestCase;
use Flowcode\UserBundle\Exception\ExistentUserException;
use Flowcode\UserBundle\Entity\UserStatus;
use Flowcode\UserBundle\Exception\InvalidTokenException;
use Flowcode\UserBundle\Exception\InexistentUserException;

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
        $this->assertNotEquals("123456", $user->getPassword());
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
        $user->setPlainPassword("123456");
        $user->setEmail("pepe@pepe.com");

        $userBefore = $this->userService->findByUsername("pepe");
        $this->assertNull($userBefore);

        $userAfter = $this->userService->create($user);
        $this->assertNotNull($userAfter);
        $this->assertEquals("pepe", $userAfter->getUsername());
        $this->assertEquals("pepe@pepe.com", $userAfter->getEmail());
        $this->assertNotEquals("123456", $userAfter->getPassword());
        $this->assertEquals(UserStatus::INACTIVE, $userAfter->getStatus());

        $this->assertNotNull($userAfter->getGroups());
        $this->assertEquals(1, sizeof($userAfter->getRoles()));
        $this->assertEquals("ROLE_USER", $userAfter->getRoles()[0]);
    }

    public function testCreate_usernameExistent_throwException()
    {
        $user = $this->userService->createNewUser();
        $username = "user";
        $user->setUsername($username);
        $user->setPlainPassword("123456");
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
        $user->setPlainPassword("123456");
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

    public function testActivateUserRegister_userOk_activateUser()
    {
        $user = $this->userService->findByUsername('user2');
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());
        $this->userService->activateUserRegister($user->getId(), $user->getRegisterToken());

        $this->assertNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::ACTIVE, $user->getStatus());
    }

    public function testActivateUserRegister_withInvalidUser_throwException()
    {
        $user = $this->userService->findByUsername('user2');
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());

        $this->setExpectedException(InexistentUserException::class);
        $this->userService->activateUserRegister(9999, $user->getRegisterToken());
    }

    public function testActivateUserRegister_withInvalidToken_throwException()
    {
        $user = $this->userService->findByUsername('user2');
        $this->assertNotNull($user->getRegisterToken());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());

        $this->setExpectedException(InvalidTokenException::class);
        $this->userService->activateUserRegister($user->getId(), 'sarasa');

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

        $this->userService->checkForgot($user->getId(), $user->getForgotToken());

        $this->assertNotNull($user->getForgotToken());
    }

    public function testCheckForgot_withInvalidUser_throwException()
    {
        $user = $this->userService->findByUsername('user3');
        $this->assertNotNull($user->getForgotToken());

        $this->setExpectedException(InexistentUserException::class);
        $this->userService->checkForgot(9999, $user->getForgotToken());

        $this->assertNotNull($user->getForgotToken());
    }

    public function testCheckForgot_withInvalidToken_throwException()
    {
        $user = $this->userService->findByUsername('user3');
        $this->assertNotNull($user->getForgotToken());

        $this->setExpectedException(InvalidTokenException::class);
        $this->userService->checkForgot($user->getId(), 'sarasa');

        $this->assertNotNull($user->getForgotToken());
    }

    public function testRecoverPassword_withUserAndTokenOk_generateNewPassword()
    {
        $user = $this->userService->findByUsername('user3');
        $initialPassword = "123456";
        $newPassword = "1234567";
        $encoder = $this->getContainer()->get('security.password_encoder');
        $this->assertNotNull($user->getForgotToken());

        $this->userService->recoverPassword($user, $user->getForgotToken(), $newPassword);

        $userAfter = $this->userService->findByUsername('user3');

        $this->assertNull($userAfter->getForgotToken());
        $this->assertFalse($encoder->isPasswordValid($userAfter, $initialPassword));
        $this->assertTrue($encoder->isPasswordValid($userAfter, $newPassword));
    }

    public function testRecoverPassword_withTokenNull_throwException()
    {
        $user = $this->userService->findByUsername('user');
        $newPassword = "123456";
        $this->assertNull($user->getForgotToken());

        $this->setExpectedException(InvalidTokenException::class);
        $this->userService->recoverPassword($user, $user->getForgotToken(), $newPassword);
    }

    public function testRecoverPassword_withDifferentToken_throwException()
    {
        $user = $this->userService->findByUsername('user3');
        $newPassword = "123456";
        $this->assertNotNull($user->getForgotToken());

        $this->setExpectedException(InvalidTokenException::class);
        $this->userService->recoverPassword($user, "sarasa", $newPassword);
    }
}
