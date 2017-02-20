<?php

namespace Flowcode\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;
use Flowcode\UserBundle\Entity\RoleInterface;

/**
 * User Group Service
 */
class UserGroupService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;
    protected $userGroupClass;
    protected $userGroupRepository;

    public function __construct(EntityManager $em, ContainerInterface $container, EntityRepository $userGroupRepository, $userGroupClass)
    {
        $this->em = $em;
        $this->container = $container;
        $this->userGroupClass = $userGroupClass;
        $this->userGroupRepository = $userGroupRepository;
    }

    public function findByName($nameGroup)
    {
        return $this->userGroupRepository->findOneBy(array('name' => $nameGroup));
    }

    public function create($name)
    {
        $userGroup = $this->findByName($name);
        if ($userGroup) {
            return $userGroup;
        }
        $class = $this->userGroupClass;
        $userGroup = new $class($name);
        $this->em->persist($userGroup);
        $this->em->flush();
        return $userGroup;
    }
}
