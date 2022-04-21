<?php

namespace App\Controller;

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


class TeamController extends OverrideApiController
{

    /**
     * @Route("/admin/teams", name="teams_index_admin")
     */
    public function teams_index_admin(TeamRepository $teamRepository, CategoryRepository $categoryRepository,  SerializerInterface $serializer): Response
    {
        return $this->render("admin/teams/index.html.twig", ["categories"=>$categoryRepository->findAll(), "teams"=>$teamRepository->findBy([],["Category"=>"DESC"])]);

    }

    /**
     * @Route("/teams", name="teams_index")
     */
    public function public_index(TeamRepository $teamRepository, SerializerInterface $serializer): Response
    {
        return $this->render("equipes/index.html.twig");

    }

    /**
     * @Route("/equipe/{id}", name="teams_show")
     */
    public function equipe_show(string $id, TeamRepository $teamRepository, SerializerInterface $serializer): Response
    {

        return $this->render("equipes/show.html.twig", ["equipe"=>$teamRepository->find($id)]);

    }

    /**
     * @Route("/api/teams", name="teams_api")
     */
    public function index(TeamRepository $teamRepository, SerializerInterface $serializer): Response
    {
       return $this->send($serializer->serialize($teamRepository->findAll(),'json',['groups' => ['team','category']]));
    }

    /**
     * @Route("/api/team/{idTeam}", name="teams_api_show")
     */
    public function show(string $idTeam, TeamRepository $teamRepository, SerializerInterface $serializer): Response
    {
        return $this->send($serializer->serialize($teamRepository->find($idTeam),'json',['groups' => ['team']]));
    }


    /**
     * @Route("/api/team/{idTeam}/matchs", name="teams_api_show_match")
     */
    public function show_match(string $idTeam, MeetRepository $meetRepository, TeamRepository $teamRepository, SerializerInterface $serializer): Response
    {
        return $this->send($serializer->serialize($meetRepository->findByTeam($idTeam),'json',['groups' => ['matchs']]));
    }


}
