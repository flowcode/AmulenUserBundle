<?php

namespace Flowcode\UserBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userGroupService = $this->container->get('flowcode.user.group');
        $roleService = $this->container->get('flowcode.role');

        $userGroup = $userGroupService->create("group_user");
        $role = $roleService->create("ROLE_USER");
        $userGroup->addRole($role);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
