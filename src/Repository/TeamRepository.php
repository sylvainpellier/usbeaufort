<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Team $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Team $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Team[] Returns an array of Team objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Team
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllCriterias($category_id, $phase_id)
    {
        $query =  $this->createQueryBuilder('t')
            ->join('t.Meets', 'team_a')
            ->join('m.TeamB', 'team_b');

        $parameters = [];
        if($category_id)
        {
            $query->andWhere('team_a.Category = :group_id');
            $query->andWhere('team_b.Category = :group_id');
            $parameters["group_id"] = $category_id;
        }

        if($phase_id)
        {
            $query->andWhere('m.Phase = :phase_id');
            $parameters["phase_id"] = $phase_id;
        }

        if($poule)
        {
            $query->andWhere('m.Poule = :poule');
            $parameters["poule"] = $poule;
        }

        $query->setParameters($parameters);

        return $query->getQuery()
            ->getResult();
    }
}
