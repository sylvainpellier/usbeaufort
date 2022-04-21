<?php

namespace App\Repository;

use App\Entity\Phase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Phase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phase[]    findAll()
 * @method Phase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phase::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Phase $entity, bool $flush = true): void
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
    public function remove(Phase $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Phase[] Returns an array of Phase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findEchiquier()
    {
        return $this->createQueryBuilder('p')
            ->join('p.Type',"t")
            ->where("t.format = 'echiquier'")
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Phase
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
