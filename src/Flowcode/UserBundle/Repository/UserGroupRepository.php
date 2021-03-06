<?php

namespace Flowcode\UserBundle\Repository;

use Amulen\UserBundle\Entity\User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserGroupRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByGroup($group)
    {
        $qb = $this->createQueryBuilder("gr");
        $qb->join("AmulenUserBundle:User", "u", "WITH", "1=1");
        $qb->join("u.groups", "gr2");
        $qb->where("gr.id IN (:group_id)");
        $qb->andWhere("gr2.id = gr.id");
        $qb->setParameter("group_id", $group);
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }   
}
