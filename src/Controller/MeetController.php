<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Repository\CategoryRepository;
use App\Repository\MeetRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManager;
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



class MeetController extends OverrideApiController
{

    /**
     * @Route("/admin/match/update/{id}", name="admin_update_match")
     */
    public function admin_update_match(string $id, Request $request, MeetRepository $meetRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $meet = $meetRepository->find($id);
        $meet->setScoreA((int)$request->get("scoreA") );
        $meet->setScoreB((int)$request->get("scoreB"));
        $meet->setPenaltyA((int)$request->get("penaltyA"));
        $meet->setPenaltyB((int)$request->get("penaltyB"));
        $meet->setArbitre($request->get("arbitre"));
        $entityManager->persist($meet);
        $entityManager->flush();
        $this->addFlash("success","Match mis à jour");

        $referer = $request->headers->get('referer');
        return $this->redirect($referer."#meet_".$meet->getId());
        return $this->redirectToRoute("app_admin_category_phase_groupe",["idPhase"=>$meet->getPhase()->getId(),"idCategory"=>$meet->getTeamA()->getCategory()->getId(),"groupe"=>$meet->getPoule()->getId()]);
    }

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
       return $this->send($serializer->serialize($meetRepository->findAll(),'json',['groups' => ['matchs']]));
    }
}
