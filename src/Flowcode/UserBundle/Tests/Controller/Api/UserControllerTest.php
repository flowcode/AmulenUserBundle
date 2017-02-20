<?php

namespace Flowcode\UserBundle\Tests\Controller\Api;

use Flowcode\UserBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;
use Flowcode\UserBundle\Entity\ResponseCode;
use Flowcode\UserBundle\Entity\UserStatus;

class UserControllerTest extends BaseTestCase
{
    private $userService;

    public function setUp()
    {
        parent::setUp();
        $this->userService = $this->getContainer()->get('flowcode.user');
    }

    public function testRegister_userOk_createUser()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_register');

        $params = [
            "username" => 'juancho',
            "plainPassword" => '123456',
            "email" => 'juancho@juancho.com',
            "ACCEPT" => 'application/json'
        ];

        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $user = $this->userService->findByUsername("juancho");
        $responseContent = json_decode($response->getContent());
        if ($user == null) {
            $this->fail('User not registered');
        }

        $this->assertEquals(true, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_REGISTER_OK, $responseContent->code);

        $this->assertEquals("juancho", $user->getUsername());
        $this->assertEquals("juancho@juancho.com", $user->getEmail());
        $this->assertNotEquals("1234", $user->getPassword());
        $this->assertEquals(UserStatus::IN_REGISTER, $user->getStatus());
    }

    public function testRegister_userAlreadyCreated_notCreateUser()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_register');

        $params = [
            "username" => 'user',
            "plainPassword" => '123456',
            "email" => 'juancho@juancho.com',
            "ACCEPT" => 'application/json'
        ];


        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent());

        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(false, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_REGISTER_IN_SYSTEM, $responseContent->code);
    }

    public function testRegister_userWithInvalidPassword_notCreateUser()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_register');

        $params = [
            "username" => 'juancho',
            "plainPassword" => '1234',
            "email" => 'juancho@juancho.com',
            "ACCEPT" => 'application/json'
        ];

        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent());

        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(false, $responseContent->success);
    }

    public function testLogin_userOk_loginUser()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_login');
        $params = [
            "username" => 'user',
            "password" => '123456',
            "ACCEPT" => 'application/json'
        ];

        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertNotEmpty(true, $responseContent->token);
    }

    public function testLogin_userInvalid_notLoginUser()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_login');
        $params = [
            "username" => 'user',
            "password" => '12345678',
            "ACCEPT" => 'application/json'
        ];

        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testForgot_userOk_checkForgotTokenSet()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_forgot');

        $params = [
            "email" => 'user@user.com',
            "ACCEPT" => 'application/json'
        ];


        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseContent = json_decode($response->getContent());
        $this->assertEquals(true, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_FORGOT_SEND, $responseContent->code);

        $user = $this->userService->findByUsername("user");
        if ($user == null) {
            $this->fail('User not registered');
        }
        $this->assertNotNull($user->getForgotToken());
    }

    public function testForgot_userFail_forgotTokenNotSet()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_forgot');

        $params = [
            "email" => 'userSarasas@user.com',
            "ACCEPT" => 'application/json'
        ];


        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $responseContent = json_decode($response->getContent());
        $this->assertEquals(false, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_NOT_FOUND, $responseContent->code);
    }

    public function testRecover_tokenOk_updatePassword()
    {
        $encoder = $this->getContainer()->get('security.password_encoder');
        $initialPassword = "123456";
        $newPassword = "12345567";
        $apiRoute = $this->getUrl('flowcode_user_api_recover');
        $user = $this->userService->findByUsername("user3");

        $params = [
            "email" => $user->getEmail(),
            "forgotToken" => $user->getForgotToken(),
            "plainPassword" => $newPassword,
            "ACCEPT" => 'application/json'
        ];

        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(true, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_PASSWORD_CHANGED, $responseContent->code);

        $this->getContainer()->get('doctrine')->getManager()->refresh($user);
        $userAfter = $this->userService->findByUsername("user3");

        $this->assertEquals("user3", $userAfter->getUsername());
        $this->assertEquals("user3@user.com", $userAfter->getEmail());
        $this->assertNull($userAfter->getForgotToken());
        $this->assertFalse($encoder->isPasswordValid($userAfter, $initialPassword));
        $this->assertTrue($encoder->isPasswordValid($userAfter, $newPassword));
    }

    public function testRecover_differentToken_NotUpdatePassword()
    {
        $encoder = $this->getContainer()->get('security.password_encoder');
        $initialPassword = "123456";
        $newPassword = "1234567";
        $apiRoute = $this->getUrl('flowcode_user_api_recover');
        $user = $this->userService->findByUsername("user3");

        $params = [
            "email" => $user->getEmail(),
            "forgotToken" => "sarasa",
            "plainPassword" => $newPassword,
            "ACCEPT" => 'application/json'
        ];

        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent());
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(false, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_PASSWORD_NOT_CHANGED, $responseContent->code);

        $this->getContainer()->get('doctrine')->getManager()->refresh($user);
        $userAfter = $this->userService->findByUsername("user3");

        $this->assertEquals("user3", $userAfter->getUsername());
        $this->assertEquals("user3@user.com", $userAfter->getEmail());
        $this->assertNotNull($userAfter->getForgotToken());
        $this->assertTrue($encoder->isPasswordValid($userAfter, $initialPassword));
        $this->assertFalse($encoder->isPasswordValid($userAfter, $newPassword));
    }

    public function testRecover_inexistentEmail_NotUpdatePassword()
    {
        $newPassword = "123456";
        $apiRoute = $this->getUrl('flowcode_user_api_recover');
        $user = $this->userService->findByUsername("user3");

        $params = [
            "email" => "sarasa@pepe.com",
            "forgotToken" => $user->getForgotToken(),
            "plainPassword" => $newPassword,
            "ACCEPT" => 'application/json'
        ];

        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent());
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(false, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_NOT_FOUND, $responseContent->code);
    }
}
