<?php

namespace Flowcode\UserBundle\Repository;

use Amulen\UserBundle\Entity\User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{


    /**
     * Find by username.
     * @param  string $username A username.
     * @return User           The user.
     */
    public function findByUsername($username)
    {
        $qb = $this->createQueryBuilder("u");
        $qb->where("(u.username = :username OR u.email = :email)")
            ->setParameter("username", $username)
            ->setParameter("email", $username);
        $qb->andWhere("u.status = :status")->setParameter("status", 1);
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findByUniques($username, $email, $dni = null, $code = null)
    {
        $qb = $this->createQueryBuilder("u");
        $qb->orWhere("u.username = :username")->setParameter("username", $username);
        $qb->orWhere("u.email = :email")->setParameter("email", $email);
        if ($dni) {
            $qb->orWhere("u.dni = :dni")->setParameter("dni", $dni);
        }
        if ($code) {
            $qb->orWhere("u.code = :code")->setParameter("code", $code);
        }
        return $qb->getQuery()->getResult();
    }
}
