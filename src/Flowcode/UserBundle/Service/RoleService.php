<?php

namespace Flowcode\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;

class RoleService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;
    protected $roleClass;
    protected $roleRepository;

    public function __construct(EntityManager $em, ContainerInterface $container, EntityRepository $roleRepository, $roleClass)
    {
        $this->em = $em;
        $this->container = $container;
        $this->roleClass = $roleClass;
        $this->roleRepository = $roleRepository;
    }

    public function findByName($name)
    {
        return $this->roleRepository->findOneBy(array('name' => $name));
    }

    public function create($name)
    {
        $role = $this->findByName($name);
        if ($role) {
            return $role;
        }
        $class = $this->roleClass;
        $role = new $class($name);
        $this->em->persist($role);
        $this->em->flush();
        return $role;
    }
}
