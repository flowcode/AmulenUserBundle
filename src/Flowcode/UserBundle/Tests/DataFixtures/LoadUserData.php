<?php

namespace Flowcode\UserBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userService = $this->container->get('flowcode.user');

        $user = $userService->createNewUser();
        $user->setUsername("juan");
        $user->setPlainPassword("1234");
        $user->setEmail("juan@juan.com");
        $userService->create($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
