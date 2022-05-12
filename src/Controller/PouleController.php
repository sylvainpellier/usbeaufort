<?php

namespace App\Controller;

use App\Repository\PositionRepository;
use App\Repository\PouleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PouleController extends OverrideApiController
{


    /**
     * @Route("/api/poule/{idPoule}", name="app_admin_phase_groupe_update")
     */
    public function app_admin_phase_groupe_update( Request $request, EntityManagerInterface $entityManager, string $idPoule, PouleRepository $pouleRepository, SerializerInterface $serializer): Response
    {
        $poule = $pouleRepository->find($idPoule);
        $poule->setName( $request->get("pouleName") );
        $entityManager->persist($poule);
        $entityManager->flush();

        return $this->redirectToRoute("app_admin_category_phase",["idCategory"=>$poule->getPhase()->getCategory()->getId(),"idPhase"=>$poule->getPhase()->getId()]);

    }

    /**
     * @Route("/api/poules", name="poules_api_show")
     */
    public function show( PouleRepository $pouleRepository, SerializerInterface $serializer): Response
    {
        return $this->send($serializer->serialize($pouleRepository->findAll(),'json',['groups' => ['poules']]));
    }

    /**
     * @Route("/api/poule/{id}/positions", name="poules_api_show")
     */
    public function positions( string $id, PositionRepository $positionRepository, PouleRepository $pouleRepository, SerializerInterface $serializer): Response
    {
        $data = [];
        $data["from"] = $positionRepository->findBy(["PouleFrom"=>$id]);
        $data["to"] = $positionRepository->findBy(["PouleTo"=>$id]);
        return $this->send($serializer->serialize($data,'json',['groups' => ['positions']]));
    }
}
