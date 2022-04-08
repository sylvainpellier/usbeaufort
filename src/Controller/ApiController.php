<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\Team;
use App\Repository\MeetRepository;
use App\Repository\TeamRepository;
use function dump;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use function var_dump;

class ApiController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/data/meets", name="app_api_meets")
     */
    public function index(Request $request, MeetRepository $meetRepository,  SerializerInterface $serializer): Response
    {

        $category = $request->get("category");
        $phase = $request->get("phase");
        $poule = $request->get("poule");

       $data = $meetRepository->findAllCriterias($category, $phase, $poule);

        return $this->json(json_decode($serializer->serialize($data, 'json', ['groups' => 'matchs'])));

    }



    /**
     * @Route("/api/data/phase", name="app_api_phase")
     */
    public function old(Request $request,  MeetRepository $meetRepository, SerializerInterface $serializer): Response
    {

        $category = $request->get("category");
        $phase = $request->get("phase");


        $conn = $this->entityManager->getConnection();

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
            SELECT * FROM  usb_meets matchs
            WHERE 
            matchs.team_a_id = :team_id OR matchs.team_b_id = :team_id AND
                matchs.phase_id = :phase
            ';


        $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery(['phase' => $phase, 'team_id' => $team["id"]]);
            $resultats = $resultSet->fetchAll();

            foreach($resultats as $match)
            {
                if($team["id"] === $match["team_a_id"])
                {
                    $teams[$key]["but_pour"] += $match["score_a"];
                    $teams[$key]["but_contre"] += $match["score_b"];

                    if( ( $match["score_a"] > $match["score_b"] || ($match["score_a"] == $match["score_b"] && $match["penalty_a"] == $match["penalty_b"] ) ))
                    {
                        $teams[$key]["pts"] += 3;
                        $teams[$key]["victoire"]++;
                    } else if( $match["score_a"] === $match["score_b"])
                    {
                        $teams[$key]["pts"] += 1;
                        $teams[$key]["nul"] ++;
                    } else
                    {
                        $teams[$key]["defaite"]++;
                    }
                }

            }

        }

        return $this->json(json_decode($serializer->serialize($teams, 'json', ['groups' => 'matchs'])));

    }
}
