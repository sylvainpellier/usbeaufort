<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Repository\CategoryRepository;
use App\Repository\MeetRepository;
use App\Repository\TeamRepository;
use function json_encode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use function var_dump;



class MeetController extends OverrideApiController
{

    /**
     * @Route("/matchs/{id}/phase/{phase}/groupe/{groupe}", name="display_matchs")
     */
    public function display_phase(string $id, string $phase, string $groupe, CategoryRepository $categoryRepository, SerializerInterface $serializer): Response
    {
        return $this->render("match_phase.html.twig", ["category"=>$categoryRepository->find($id), "phase" => $phase, "groupe" => $groupe]);
    }

    /**
     * @Route("/api/matchs", name="api_meets_all")
     */
    public function index(MeetRepository $meetRepository, SerializerInterface $serializer): Response
    {
       return $this->send($meetRepository->findAll(),["matchs"], $serializer);
    }
}
