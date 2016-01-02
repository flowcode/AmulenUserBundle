<?php

namespace Flou\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Amulen\UserBundle\Entity\User;
use Flowcode\UserBundle\Service\UserNotificationService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * User Service
 */
class UserService implements UserProviderInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    public function __construct(EntityManager $em, UserPasswordEncoder $encoder, ContainerInterface $container)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->container = $container;
    }

    /**
     * Find al users with pagination options.
     * @param  integer $page [description]
     * @param  integer $max  [description]
     * @return ArrayCollection        users.
     */
    public function findAll($page = 1, $max = 50)
    {
        $offset = (($page-1) * $max);
        $users = $this->getEm()->getRepository("AmulenUserBundle:User")->findBy(array(), array(), $max, $offset);
        return $users;
    }

    /**
     * Find by id.
     * @param  integer $id
     * @return User        user.
     */
    public function findById($id)
    {
        return $this->getEm()->getRepository("AmulenUserBundle:User")->find($id);
    }

    /**
     * Create a new user.
     * @param  User   $user the user instance.
     * @return User       the user instance.
     */
    public function create(User $user)
    {
        /* handle encode */
        $user = $this->encode($user);

        $this->getEm()->persist($user);
        $this->getEm()->flush();

        return $user;
    }

    public function encode(User $user)
    {
        if (strlen($user->getPlainPassword()) > 0) {
            $encoded1 = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded1);
        }
        return $user;
    }

    public function update(User $user)
    {
        /* handle encode */
        $user = $this->encode($user);

        $this->getEm()->flush();
        return $user;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->getEm()->getRepository("AmulenUserBundle:User")->findByUsername($username);
        return $user;
    }

    public function resetPasssword(User $user)
    {
        $plainPassword = $this->generateRandomPassword();
        $user->setPlainPassword($plainPassword);
        $this->update($user);
        $notificationService = $this->container->get("amulen.user.notification");
        $notificationService->notifyPasswordReset($user, $plainPassword);

        return true;
    }

    /**
    * Geneate Radmon password.
    */
    public function generateRandomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    /**
     * Check if is valid username.
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function validUsername($username)
    {
        if (strlen($username) < 4) {
            return false;
        }
        return !preg_match('/[^A-Za-z0-9_\\-]/', $username);
    }

    /**
     * Check if is unique.
     * @param  [type]  $username [description]
     * @param  [type]  $email    [description]
     * @param  [type]  $phone    [description]
     * @param  [type]  $dni      [description]
     * @param  [type]  $code     [description]
     * @return boolean           [description]
     */
    public function isUnique($username, $email, $dni = null, $code = null)
    {
        $entities = $this->getEm()->getRepository("AmulenUserBundle:User")->findByUniques($username, $email, $dni, $code);
        return count($entities) <= 0;
    }

    /**
     * Get by Code.
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    public function getByCode($code)
    {
        $user = $this->getEm()->getRepository("AmulenUserBundle:User")->findOneBy(array('code' => $code));
        return $user;
    }

    /**
     * Upload user image.
     *
     * @param User $entity
     * @return User
     */
    public function uploadImage(User $entity)
    {

        /* the file property can be empty if the field is not required */
        if (null === $entity->getFile()) {
            return $entity;
        }

        $uploadBaseDir = $this->container->getParameter("user_avatar_basedir");
        $uploadDir = $this->container->getParameter("user_avatar_dir");

        /* set the path property to the filename where you've saved the file */
        $filename = $entity->getFile()->getClientOriginalName();
        $extension = $entity->getFile()->getClientOriginalExtension();

        $imageName = md5($filename . time()) . '.' . $extension;

        $entity->setAvatar($uploadDir . $imageName);
        $entity->getFile()->move($uploadBaseDir . $uploadDir, $imageName);

        $entity->setFile(null);

        return $entity;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Amulen\UserBundle\Entity\User';
    }


    /**
     * Set entityManager.
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * Get entityManager.
     * @return EntityManager Entity manager.
     */
    public function getEm()
    {
        return $this->em;
    }
}
