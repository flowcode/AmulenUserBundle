<?php

namespace Flowcode\UserBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Flowcode\UserBundle\Entity\UserStatus;

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
        $user->setUsername("user");
        $user->setPlainPassword("1234");
        $user->setEmail("user@user.com");
        $user->setStatus(UserStatus::ACTIVE);

        $userService->create($user);


        $user2 = $userService->createNewUser();
        $user2->setUsername("user2");
        $user2->setPlainPassword("1234");
        $user2->setEmail("user@user2.com");
        $user2->setRegisterToken("token");
        $user2->setStatus(UserStatus::IN_REGISTER);
        $userService->create($user2);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
