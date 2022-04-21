<?php

namespace App\Controller;

use App\Repository\PositionRepository;
use App\Repository\PouleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PouleController extends OverrideApiController
{
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
