<?php

namespace App\Repository;

use App\Entity\Meet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use function var_dump;

/**
 * @method Meet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meet[]    findAll()
 * @method Meet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meet::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Meet $entity, bool $flush = true): void
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
    public function remove(Meet $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findBySpecial($ordre)
    {
        return $this->createQueryBuilder('t')
            ->join('t.Phase', 'phase')
            ->join("phase.Type","tp")
            ->where("tp.format != 'echiquier' ")
            ->andWhere('phase.ordre = :ordre')
            ->setParameter("ordre",$ordre)
            ->orderBy('t.Tour', 'ASC')
            ->addOrderBy('t.TeamA','DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllCriterias($category_id, $phase_id, $poule = null, $order = "m.Tour" )
    {

        $query =  $this->createQueryBuilder('m');
        if($category_id)
        {
            $query->join('m.TeamA', 'team_a')
            ->join('m.TeamB', 'team_b');
        }

        $query->join('m.Poule', 'poule');
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
            $query->andWhere("poule.id = :poule");
            $parameters["poule"] = $poule;
        }

        $query->orderBy($order,"ASC");
        $query->setParameters($parameters);



        return $query->getQuery()
                ->getResult();
    }


    public function findAllCriteriasByPosition($category_id, $phase_id, $poule = null, $order = "m.Tour")
    {

        $query =  $this->createQueryBuilder('m');
                  $query->join('m.PositionA', 'positionA');
                $query->join('m.PositionB', 'positionB');


        $parameters = [];

        if($phase_id)
        {
            $query->andWhere('m.Phase = :phase_id');
            $parameters["phase_id"] = $phase_id;
        }



        $query->orderBy($order,"ASC");
        $query->setParameters($parameters);



        return $query->getQuery()
            ->getResult();
    }


    public function findGroupes($category_id, $phase_id)
    {
        $query =  $this->createQueryBuilder('m')
            ->join('m.Poule', 'p')
           ->select("max(p.id) as poule");




        if($phase_id)
        {
            $query->andWhere('m.Phase = :phase_id');
            $parameters["phase_id"] = $phase_id;
        }

        $query->groupBy("p.id");
        $query->setParameters($parameters);



        return $query->getQuery()
            ->getResult();
    }

    public function findByTeam($idTeam)
    {
        $query =  $this->createQueryBuilder('m')
            ->join('m.TeamA', 'team_a')
            ->join('m.TeamB', 'team_b');

        $parameters = [];

            $query->andWhere('team_a.id = :id');
            $query->orWhere('team_b.id = :id');
            $parameters["id"] = $idTeam;

        $query->orderBy("m.Tour","ASC");

        $query->setParameters($parameters);

        return $query->getQuery()
            ->getResult();
    }

}
