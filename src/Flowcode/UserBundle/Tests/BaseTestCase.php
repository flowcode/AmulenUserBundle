<?php

namespace Flowcode\UserBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class BaseTestCase extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $classes = array(
            'Flowcode\UserBundle\Tests\DataFixtures\LoadUserData',
            'Flowcode\UserBundle\Tests\DataFixtures\LoadUserGroupData',
        );
        $this->loadFixtures($classes, null, 'doctrine', ORMPurger::PURGE_MODE_TRUNCATE);
        $this->client = $this->createClient();
    }
}
