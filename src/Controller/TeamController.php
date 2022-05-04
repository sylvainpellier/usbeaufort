<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\CategoryRepository;
use App\Repository\MeetRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use function json_encode;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/admin/team/{id}", name="app_admin_update_team")
     */
    public function app_admin_update_team(string $id, Request $request, EntityManagerInterface $entityManager, TeamRepository $teamRepository, CategoryRepository $categoryRepository): Response
    {
        $team = $teamRepository->find($id);
        $form = $this->createForm(TeamType::class, $team);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $team = $form->getData();
            $entityManager->persist($team);
            $entityManager->flush();
            $this->addFlash("success","Équipe modifiée avec succès");
            return $this->redirectToRoute("app_admin_category",["id"=>$team->getCategory()->getId()]);
        }

        return $this->render('admin/teams/update.html.twig', [
            'form' => $form->createView(),
            'team' => $team,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/team/delete/{id}", name="admin_team_delete")
     */
    public function admin_team_delete(string $id,CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, TeamRepository $teamRepository): Response
    {
        $team = $teamRepository->find($id);
        if($team)
        {
            $entityManager->remove($team);
            $entityManager->flush();
        }
        return $this->redirectToRoute("app_admin_category",["categories"=>$categoryRepository->findAll(),"id"=>$team->getCategory()->getId()]);
    }

    /**
     * @Route("/admin/addTeam/{idCategory}", name="app_add_team")
     */
    public function app_add_team(Request $request, EntityManagerInterface $entityManager, string $idCategory, CategoryRepository $categoryRepository): Response
    {

        if($request->get("nameTeam") && $request->get("idCategory"))
        {
            $category = $categoryRepository->find($request->get("idCategory"));

            $team = new Team();
            $team->setCategory($category);
            $team->setName($request->get("nameTeam"));
            $team->setRang($request->get("rang"));
            $entityManager->persist($team);
            $entityManager->flush();

            $this->addFlash("success","Équipe ajoutée avec succès");
            return $this->redirectToRoute("app_admin_category",["categories"=>$categoryRepository->findAll(),"id"=>$category->getId()]);

        } else
        {
            return $this->render("admin/addTeam.html.twig",["categories"=>$categoryRepository->findAll(),"category"=>$categoryRepository->find($idCategory)]);
        }

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
       return $this->send($serializer->serialize($teamRepository->findBy([],["Name"=>"ASC"]),'json',['groups' => ['team','category']]));
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
