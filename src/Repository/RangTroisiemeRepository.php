<?php

namespace App\Repository;

use App\Entity\RangTroisieme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RangTroisieme|null find($id, $lockMode = null, $lockVersion = null)
 * @method RangTroisieme|null findOneBy(array $criteria, array $orderBy = null)
 * @method RangTroisieme[]    findAll()
 * @method RangTroisieme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RangTroisiemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RangTroisieme::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(RangTroisieme $entity, bool $flush = true): void
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
    public function remove(RangTroisieme $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return RangTroisieme[] Returns an array of RangTroisieme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RangTroisieme
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
