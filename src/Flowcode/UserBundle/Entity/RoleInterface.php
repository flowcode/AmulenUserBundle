<?php

namespace Flowcode\UserBundle\Entity;

interface RoleInterface
{

    public function getId();

    public function setName($name);

    public function getName();

    function __toString();
}
