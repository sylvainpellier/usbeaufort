<?php

namespace App\Controller;

use App\Repository\MeetRepository;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PositionController extends AbstractController
{
    /**
     * @Route("/position/echange", name="app_position_echange")
     */
    public function index(Request $request, MeetRepository $meetRepository, EntityManagerInterface $entityManager, PositionRepository $positionRepository): Response
    {
        $id = $request->get("id");
        $from = $request->get("from");
        $to = $request->get("to");

        if($from && $to)
        {
            $from_posistion = $positionRepository->find($from);
            $to_posistion = $positionRepository->find($to);
            if($from_posistion && $to_posistion)
            {
                $oldFrom = $from_posistion->getPouleTo();
                $oldTo = $to_posistion->getPouleTo();

                $meetsAFrom = $meetRepository->findBy(["PositionA"=>$from_posistion]);
                $meetsBFrom = $meetRepository->findBy(["PositionB"=>$from_posistion]);

                $meetsATo = $meetRepository->findBy(["PositionA"=>$to_posistion]);
                $meetsBTo = $meetRepository->findBy(["PositionB"=>$to_posistion]);



                foreach($meetsAFrom as $m)
                {
                    $m->setPositionA($to_posistion);
                    $entityManager->persist($m);
                    $entityManager->flush();
                }

                foreach($meetsBFrom as $m)
                {
                    $m->setPositionB($to_posistion);
                    $entityManager->persist($m);
                    $entityManager->flush();
                }

                foreach($meetsATo as $m)
                {
                    $m->setPositionA($from_posistion);
                    $entityManager->persist($m);
                    $entityManager->flush();
                }

                foreach($meetsBTo as $m)
                {
                    $m->setPositionB($from_posistion);
                    $entityManager->persist($m);
                    $entityManager->flush();
                }



                $from_posistion->setPouleTo( $oldTo );
                $to_posistion->setPouleTo( $oldFrom );

                $entityManager->persist($from_posistion);
                $entityManager->persist($to_posistion);

                $entityManager->flush();

                $this->addFlash("success","Positions échangées avec succès");
            }
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer."#poule_".$id);
    }
}
