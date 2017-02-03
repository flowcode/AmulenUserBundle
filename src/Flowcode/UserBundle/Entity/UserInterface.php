<?php

namespace Flowcode\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface as ParentUserInterface;

interface UserInterface extends ParentUserInterface, \Serializable
{
    /**
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getId();
    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username);
    /**
     * Gets the username.
     *
     * @return string 
     */
    public function getUsername();
    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email);
    /**
     * Gets email.
     *
     * @return string
     */
    public function getEmail();
    /**
     * Sets the plain password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPlainPassword($password);
    /**
     * Gets the plain password.
     *
     * @return string
     */
    public function getPlainPassword();
    public function getRoles();
    public function getSalt();
    public function eraseCredentials();
}
