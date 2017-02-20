<?php

namespace Flowcode\UserBundle\Entity;

use Flowcode\UserBundle\Entity\RoleInterface;

interface UserGroupInterface
{
    public function getId();
    public function getName();
    public function hasRole($role);
    public function getRoles();
    public function removeRole(RoleInterface $role);
    public function setName($name);
    public function addRole(RoleInterface $role);
    public function setRoles(array $roles);
    function __toString();
}
