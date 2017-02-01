<?php

namespace Flowcode\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserGroup
 */
class UserGroup {

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

    public function __construct($name) {
        $this->setName($name);
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role) {
        return in_array(strtoupper($role), $this->roles, true);
    }

    public function getRoles() {
        return $this->roles;
    }

    /**
     * @param \Amulen\UserBundle\Entity\Role $role
     */
    public function removeRole(\Amulen\UserBundle\Entity\Role $role) {
        $this->tags->removeElement($role);
    }

    /**
     * @param string $name
     *
     * @return \Amulen\UserBundle\Entity\UserGroup
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @param \Amulen\UserBundle\Entity\Role $role
     *
     * @return \Amulen\UserBundle\Entity\UserGroup
     */
    public function addRole(\Amulen\UserBundle\Entity\Role $role) {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return \Amulen\UserBundle\Entity\UserGroup
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;

        return $this;
    }

    function __toString() {
        return $this->id . "-" . $this->name;
    }

}
