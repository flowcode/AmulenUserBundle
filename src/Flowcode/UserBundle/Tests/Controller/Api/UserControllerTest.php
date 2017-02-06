<?php

namespace Flowcode\UserBundle\Tests\Controller\Api;

use Flowcode\UserBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;
use Flowcode\UserBundle\Entity\ResponseCode;

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
            "plainPassword" => '1234',
            "email" => 'juancho@juancho.com',
            "ACCEPT" => 'application/json'
        ];


        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();


        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $user = $this->userService->loadUserByUsername("juancho");
        $responseContent = json_decode($response->getContent());

        if ($user == null) {
            $this->fail('User not registered');
        }

        $this->assertEquals(true, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_REGISTER_OK, $responseContent->code);
        
        $this->assertEquals("juancho", $user->getUsername());
        $this->assertEquals("juancho@juancho.com", $user->getEmail());
        $this->assertNotEquals("1234", $user->getPassword());

    }

    public function testRegister_userAlreadyCreated_notCreateUser()
    {
        $apiRoute = $this->getUrl('flowcode_user_api_register');

        $params = [
            "username" => 'juan',
            "plainPassword" => '1234',
            "email" => 'juancho@juancho.com',
            "ACCEPT" => 'application/json'
        ];


        $this->client->request('POST', $apiRoute, $params);
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseContent = json_decode($response->getContent());
        $this->assertEquals(false, $responseContent->success);
        $this->assertEquals(ResponseCode::USER_REGISTER_IN_SYSTEM, $responseContent->code);
    }
}
