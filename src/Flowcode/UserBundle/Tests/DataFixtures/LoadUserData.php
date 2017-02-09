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
        $user2->setEmail("user2@user.com");
        $user2->setRegisterToken("token");
        $user2->setStatus(UserStatus::IN_REGISTER);
        $userService->create($user2);


        $user3 = $userService->createNewUser();
        $user3->setUsername("user3");
        $user3->setPlainPassword("1234");
        $user3->setEmail("user3@user.com");
        $user3->setForgotToken("token");
        $user3->setStatus(UserStatus::ACTIVE);
        $userService->create($user3);
        
        
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
