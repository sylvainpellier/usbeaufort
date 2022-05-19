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
            $teams[$key]["bonus1"] = 0;

            $sql = '
            SELECT * FROM  usb_meets matchs, usb_poules poules 
            WHERE matchs.poule_id = poules.id AND ( matchs.team_a_id = :team_id OR matchs.team_b_id = :team_id ) AND matchs.phase_id = :phase 
             
           ';

            if($groupe)
            {
                $sql .= " AND matchs.poule_id = :groupe";
            }


            $stmt = $conn->prepare($sql);

            $parameters = ['phase' => $phase->getId(), 'team_id' => $team["id"]];

            if($groupe) { $parameters["groupe"] = $groupe; }



            $resultSet = $stmt->executeQuery($parameters);
            $resultats = $resultSet->fetchAll();





            if(count($resultats) === 0 && $phase->getPhasePrecedente()){
            $teams = [];
                $poules = $phase->getPhasePrecedente()->getPoules();
                foreach($poules as $poule) {

                    foreach ($poule->getPositionsFrom() as $key1 => $position) {
                        $t = [];
                        $t["pts"] = 0;
                        $t["but_pour"] = 0;
                        $t["but_contre"] = 0;
                        $t["victoire"] = 0;
                        $t["defaite"] = 0;
                        $t["nul"] = 0;
                        $t["bonus1"] = 0;
                        $t["poule"] = $position->getPouleTo() ? $position->getPouleTo()->getId() : null;
                        $t["pouleNameString"] = $position->getPouleTo() ? $position->getPouleTo()->getName() : null;
                        $t["Name"] = $position->getRang() . ($position->getRang() > 1 ? "ème" : "er") . " de la poule " . $position->getPouleFrom()->getName();
                        $teams[] = $t;
                    }
                }


            } else {


                foreach ($resultats as $match) {




                    $teams[$key]["poule"] = $match["poule_id"];
                    $teams[$key]["pouleNameString"] = $match["name"];

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

                        if($match["team_forfait_id"]) {
                            if ($match["team_forfait_id"] !== $team["id"]) {
                                $teams[$key]["pts"] += 4;
                                $teams[$key]["victoire"]++;
                            } else {
                                $teams[$key]["defaite"]++;
                            }
                        }else if ($me && $against && !is_null($match[$me]) && !is_null($match[$against])) {
                        $teams[$key]["but_pour"] += $match["score_a"];
                        $teams[$key]["but_contre"] += $match["score_b"];



                         if (  ($match[$me] > $match[$against] || ($match[$me] === $match[$against] && $match[$mePenalty] > $match[$againstPenalty]))) {
                                $teams[$key]["pts"] += 4;
                                $teams[$key]["victoire"]++;
                            } else if ($match[$me] === $match[$against]) {
                                $teams[$key]["pts"] += 2;
                                $teams[$key]["nul"]++;
                            } else {
                                $teams[$key]["defaite"]++;
                                $teams[$key]["pts"] += 1;
                            }
                        }
                        //TODO: forfait
                    }

                }
            }



        $poules = [];


        foreach ($teams as $key => $team)
        {
            if(isset($team["poule"]))
            {
                $sql = 'SELECT * FROM  usb_poules_teams2   WHERE usb_poules_teams2.poule_id = :poule AND usb_poules_teams2.team_id = :team  ';
                $stmt = $conn->prepare($sql);
                $parameters = ['poule' => $team["poule"], 'team' => $team["id"]];

                $resultSet = $stmt->executeQuery($parameters);
                $resultatsEgalite = $resultSet->fetchAll();


                if(count($resultatsEgalite)===1)
                {
                    $team["rangForce"]=$resultatsEgalite[0]["rang"];
                }

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
            usort($poules[$key], array($this,'triClassement'));

        }

        $egalites = [];
        //CLASSEMENT FINAL
        $egalite_id = 1;
        foreach ($poules as $keyPoule => $poule)
        {
            $i = 0;
            $lastPoint = null;

            foreach($poule as $keyTeam => $team)
            {
                if($team["poule"] == $keyPoule)
                {
                    $i++;
                    $poules[$keyPoule][$keyTeam]["rang"] = $i;
                    $team["rang"] = $i;

                    if($lastPoint)
                    {
                        if($lastPoint === $team["pts"])
                        {
                            $poules[$keyPoule][$keyTeam]["egalite"] = true;
                            $poules[$keyPoule][$keyTeam-1]["egalite"] = true;

                            $poules[$keyPoule][$keyTeam]["egalite_id"] = $egalite_id;
                            $poules[$keyPoule][$keyTeam-1]["egalite_id"] = $egalite_id;

                            if(!isset($egalites[$egalite_id]) ) $egalites[$egalite_id] = [];
                            $egalites[$egalite_id][$poules[$keyPoule][$keyTeam]["id"]] = $poules[$keyPoule][$keyTeam];
                            $egalites[$egalite_id][$poules[$keyPoule][$keyTeam-1]["id"]] = $poules[$keyPoule][$keyTeam-1];


                        } else {
                            $egalite_id ++;
                        }
                    }
                    $lastPoint = $team["pts"];

                }
            }

        }

        foreach($egalites as $keyEgalite => $egalitesArray)
        {
            foreach($egalitesArray as $teamAkey => $teamA)
            {
                foreach($egalitesArray as $teamBkey => $teamB) {

                    $idWinner = $phase->checkMeets($teamA["id"],$teamB["id"]);
                    if($idWinner) {

                        foreach ($poules as $key1 => $poule) {
                            foreach($poule as $key2 => $team) {

                                if ($team["id"] == $idWinner) {

                                    $poules[$key1][$key2]["bonus1"] += 1;

                                }
                            }
                        }
                    }
                }
            }
        }

        //CLASSEMENT FINAL
        foreach ($poules as $key => $poule)
        {
            usort($poules[$key], array($this,'triClassementBonus'));

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
        $order = $request->get("orderTime") ? "m.Time" : "m.Tour";


       $matchs = $meetRepository->findAllCriterias(null, $phase, $poule,$order);

       if(count($matchs) === 0)
       {
           $matchs = $meetRepository->findAllCriteriasByPosition($category, $phase, $poule,$order);

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
