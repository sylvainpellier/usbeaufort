<?php

namespace App\Controller;

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
    public function index(Request $request, EntityManagerInterface $entityManager, PositionRepository $positionRepository): Response
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
