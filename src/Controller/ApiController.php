<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\Team;
use App\Repository\MeetRepository;
use App\Repository\PhaseRepository;
use App\Repository\TeamRepository;
use function array_key_exists;
use function array_push;
use function array_splice;
use function dump;
use function is_null;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use function usort;
use function var_dump;

class ApiController extends OverrideApiController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function data($category,$phase,$conn, $groupe = null){

        $sql = '
            SELECT teams.Name, teams.id FROM  usb_teams teams, usb_groups categories
            WHERE 
                teams.category_id = categories.id AND
                 categories.id = :category

            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['category' => $category]);

        // returns an array of arrays (i.e. a raw data set)
        $teams = $resultSet->fetchAll();

        foreach($teams as $key => $team)
        {
            $teams[$key]["pts"] = 0;
            $teams[$key]["but_pour"] = 0;
            $teams[$key]["but_contre"] = 0;
            $teams[$key]["victoire"] = 0;
            $teams[$key]["defaite"] = 0;
            $teams[$key]["nul"] = 0;

            $sql = '
            SELECT * FROM  usb_meets matchs, usb_poules poules
            WHERE matchs.poule_id = poules.id AND ( matchs.team_a_id = :team_id OR matchs.team_b_id = :team_id ) AND matchs.phase_id = :phase ';

            if($groupe)
            {
                $sql .= " AND matchs.poule_id = :groupe";
            }


            $stmt = $conn->prepare($sql);

            $parameters = ['phase' => $phase, 'team_id' => $team["id"]];

            if($groupe) { $parameters["groupe"] = $groupe; }



            $resultSet = $stmt->executeQuery($parameters);
            $resultats = $resultSet->fetchAll();

            if(count($resultats) === 0){
                        $sql = '
                    SELECT * FROM  usb_positions positions
                    WHERE phase_from_id = :phase
        
                       ';

                $parameters = ['phase' => $phase];

                $stmt = $conn->prepare($sql);
                $resultSet = $stmt->executeQuery($parameters);
                $resultats = $resultSet->fetchAll();
                $teams = [];
                foreach($resultats as $key1 => $team)
                {
                    $teams[$key1]["pts"] = 0;
                    $teams[$key1]["but_pour"] = 0;
                    $teams[$key1]["but_contre"] = 0;
                    $teams[$key1]["victoire"] = 0;
                    $teams[$key1]["defaite"] = 0;
                    $teams[$key1]["nul"] = 0;
                    $teams[$key1]["poule"] = $team["poule_to_id"];
                    $teams[$key1]["Name"] = $team["rang"].($team["rang"] > 1 ? "ème" : "er")." de la poule".$team["poule_from_id"];
                }



            } else {


                foreach ($resultats as $match) {

                    $teams[$key]["poule"] = $match["poule_id"];

                    if ($team["id"] === $match["team_a_id"]) {
                        $me = "score_a";
                        $against = "score_b";
                        $mePenalty = "penalty_a";
                        $againstPenalty = "penalty_b";
                    } else if ($team["id"] === $match["team_b_id"]) {
                        $me = "score_b";
                        $against = "score_a";
                        $mePenalty = "penalty_b";
                        $againstPenalty = "penalty_a";
                    }

                    if ($me && $against && !is_null($match[$me]) && !is_null($match[$against])) {
                        $teams[$key]["but_pour"] += $match["score_a"];
                        $teams[$key]["but_contre"] += $match["score_b"];

                        if (
                        ($match[$me] > $match[$against] || ($match[$me] === $match[$against] && $match[$mePenalty] > $match[$againstPenalty]))) {
                            $teams[$key]["pts"] += 3;
                            $teams[$key]["victoire"]++;
                        } else if ($match[$me] === $match[$against]) {
                            $teams[$key]["pts"] += 1;
                            $teams[$key]["nul"]++;
                        } else {
                            $teams[$key]["defaite"]++;
                        }
                    }

                }
            }

        }



        $poules = [];

        foreach ($teams as $key => $team)
        {
            if(isset($team["poule"]))
            {
                $poule = $team["poule"];
                if(!array_key_exists($poule,$poules)) {
                    $poules[$poule] = [];
                }
                array_push($poules[$poule],$team);
            } else{
                array_splice($teams,$key);
            }

        }


        //CLASSEMENT FINAL
        foreach ($poules as $key => $poule)
        {
            usort($poules[$key], function ($a,$b) {

                //PRENDRE EN COMPTE LES ÉGALITÉS
                //DIVERSES VOIR LE RELGEMENT
                //TODO : classement
                return $a['pts']<$b['pts'];
            });

        }

        //CLASSEMENT FINAL
        foreach ($poules as $key => $poule)
        {
            $i = 0;

            foreach($poule as $keyTeam => $team)
            {
                if($team["poule"] == $key)
                {
                    $i++;
                    $poules[$key][$keyTeam]["rang"] = $i;
                    $team["rang"] = $i;

                }
            }

        }


        return $poules;
    }
    /**
     * @Route("/api/data/meets", name="app_api_meets")
     */
    public function index(Request $request, MeetRepository $meetRepository, PhaseRepository $phaseRepository, SerializerInterface $serializer): Response
    {

        $category = $request->get("category");
        $phase = $request->get("phase");
        $poule = $request->get("poule");

       $matchs = $meetRepository->findAllCriterias($category, $phase, $poule);

       if(count($matchs) === 0)
       {
           $matchs = $meetRepository->findAllCriteriasByPosition($category, $phase, $poule);

       }



        return $this->send($serializer->serialize($matchs, 'json', ['groups' => 'matchs']),$phaseRepository->find($phase)->getType());

    }


    /**
     * @Route("/api/data/phase", name="app_api_phase")
     */
    public function old(Request $request,  MeetRepository $meetRepository, PhaseRepository $phaseRepository, SerializerInterface $serializer): Response
    {

        $category = $request->get("category");
        $phase = $request->get("phase");


        $conn = $this->entityManager->getConnection();

        $poules = $this->data($category,$phaseRepository->find($phase),$conn);


        return $this->send($serializer->serialize($poules, 'json', ['groups' => 'matchs']),$phaseRepository->find($phase)->getType());

    }
}
