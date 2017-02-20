<?php

namespace Flowcode\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

abstract class UserGroup implements UserGroupInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_group_role",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    public function __construct($name)
    {
        $this->setName($name);
        $this->roles = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
    }
    public function getRoles()
    {
        return $this->roles;
    }
    /**
     * @param \Flowcode\UserBundle\Entity\Role $role
     */
    public function removeRole(RoleInterface $role)
    {
        $this->tags->removeElement($role);
    }
    /**
     * @param string $name
     *
     * @return \Flowcode\UserBundle\Entity\UserGroupInterface
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @param \Flowcode\UserBundle\Entity\Role $role
     *
     * @return \Flowcode\UserBundle\Entity\UserGroupInterface
     */
    public function addRole(RoleInterface $role)
    {
        $this->roles[] = $role;

        return $this;
    }
    /**
     * @param array $roles
     *
     * @return \Flowcode\UserBundle\Entity\UserGroupInterface
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }
    function __toString()
    {
        return $this->id . "-" . $this->name;
    }
}
