<?php

namespace Flowcode\UserBundle\Tests\Controller\Api;

use Flowcode\UserBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

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

        if ($user == null) {
            $this->fail('User not registered');
        }

        $this->assertEquals("juancho", $user->getUsername());
        $this->assertEquals("juancho@juancho.com", $user->getEmail());
        $this->assertNotEquals("1234", $user->getPassword());
    }

}
