<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Repository\CategoryRepository;
use App\Repository\FieldRepository;
use App\Repository\MeetRepository;
use App\Repository\PhaseRepository;
use App\Repository\PouleRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function json_encode;
use function strtotime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function admin_update_match(string $id, FieldRepository $fieldRepository, Request $request, TeamRepository $teamRepository, MeetRepository $meetRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $meet = $meetRepository->find($id);
        $meet->setScoreA(($request->get("scoreA") || $request->get("scoreA") === "0") ? (int)$request->get("scoreA") : null );
        $meet->setScoreB(($request->get("scoreB")   || $request->get("scoreB")   === "0")? (int)$request->get("scoreB") : null);
        $meet->setPenaltyA(($request->get("penaltyA") || $request->get("penaltyA") === "0")  ? (int)$request->get("penaltyA") : null);
        $meet->setPenaltyB(($request->get("penaltyB") || $request->get("penaltyB") === "0") ? (int)$request->get("penaltyB") : null);

        if($meet->getFormat() === "demi")
        {
            $final_perdant = $meetRepository->findOneBy(["format"=>"final_perdant","Poule"=>$meet->getPoule()]);
            $final_gagnant = $meetRepository->findOneBy(["format"=>"final_gagnant","Poule"=>$meet->getPoule()]);

            $type = ($meet->getName() === "Demi finale A ") ? "a" : "b";

            if( (int)$request->get("scoreA") > (int)$request->get("scoreB") )
            {
                $winner = $meet->getTeamA();
                $looser = $meet->getTeamB();
            } else if( (int)$request->get("scoreA") < (int)$request->get("scoreB") )
            {
                $winner = $meet->getTeamB();
                $looser = $meet->getTeamA();
            } else if( (int)$request->get("penaltyA") > (int)$request->get("penaltyB") )
            {
                $winner = $meet->getTeamA();
                $looser = $meet->getTeamB();
            } else if( (int)$request->get("penaltyA") < (int)$request->get("penaltyB") )
            {
                $winner = $meet->getTeamB();
                $looser = $meet->getTeamA();
            }

            if($type === "a")
            {
                $final_gagnant->setTeamA($winner);
                $final_perdant->setTeamA($looser);
            } else {
                $final_gagnant->setTeamB($winner);
                $final_perdant->setTeamB($looser);
            }

            $entityManager->persist($final_perdant);
            $entityManager->persist($final_gagnant);



        }


        $meet->setArbitre($request->get("arbitre") );
        if( $request->get("field")) {

            $oldField = $meet->getField();
            $newField = $fieldRepository->find($request->get("field"));
            $meet->setField( $newField ) ;

            $findAnotherMeet = $meetRepository->findOneBy(["time"=>$meet->getTime(), "Field"=> $newField ]);
            if($findAnotherMeet)
            {
                $findAnotherMeet->setField( $oldField );
                $entityManager->persist( $findAnotherMeet );
                $this->addFlash("success","??change de terrain effectu??");

            }





        }

        $meet->setTime(strtotime($request->get("date"). " ".$request->get("time").":00"));

        $forfait = $request->get("forfait");
        if($forfait)
        {
            $meet->setTeamForfait($teamRepository->find($forfait));
        }

        $session = $request->getSession();
        $session->start();

        $entityManager->persist($meet);
        $entityManager->flush();
        $this->addFlash("success","Match mis ?? jour");

        if(  $session->get('back') )
        {
            return $this->redirect($session->get('back'));
        }
        $referer = $request->headers->get('referer');
        return $this->redirect($referer."#meet_".$meet->getId());
    }

    /**
     * @Route("/matchs/{id}/phase/{phase}/groupe/{groupe}", name="display_matchs")
     */
    public function display_phase(string $id, string $phase, EntityManagerInterface $entityManager, string $groupe, PouleRepository $pouleRepository, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository, SerializerInterface $serializer): Response
    {
        $phase = $phaseRepository->find($phase);
        $poule = $pouleRepository->find($groupe);
        $c = new ApiController($entityManager);
        $classement = $c->data($id,$phase->getId(), $conn = $entityManager->getConnection(),false);

        return $this->render("match_phase.html.twig", ["classement" => $classement, "category"=>$categoryRepository->find($id), "phase" => $phase, "poule" => $poule, "groupe" => $groupe]);
    }

    /**
     * @Route("/api/matchs", name="api_meets_all")
     */
    public function index(MeetRepository $meetRepository, SerializerInterface $serializer): Response
    {
       return $this->send($serializer->serialize($meetRepository->findBy([],["time"=>"ASC"]),'json',['groups' => ['matchs']]));
    }
}
