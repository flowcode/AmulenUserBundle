<?php

namespace Flowcode\UserBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class BaseTestCase extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $classes = array(
            'Flowcode\UserBundle\Tests\DataFixtures\LoadUserData',
        );
        $this->loadFixtures($classes);
        $this->client = $this->createClient();
    }
}
