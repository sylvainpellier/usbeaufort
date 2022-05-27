<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\FieldRepository;
use App\Repository\MeetRepository;
use App\Repository\ParamRepository;
use App\Repository\PhaseRepository;
use App\Repository\TeamRepository;
use function array_filter;
use function array_merge;
use function array_splice;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function floatval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimeController extends AbstractController
{
    /**
     * @Route("/generate/time", name="app_generate_time")
     */
    public function index(MeetRepository $meetRepository, CategoryRepository $categoryRepository, ParamRepository $paramRepository, PhaseRepository $phaseRepository, FieldRepository $fieldRepository, EntityManagerInterface $entityManager): Response
    {
        //supprime les anciens horaires
        foreach($meetRepository->findAll() as $match)
        {
            $match->setTime(null);
            $entityManager->persist($match);

        }
        $entityManager->flush();
        $countMaxBetween = (float)($paramRepository->findOneBy(["Name"=>"ratio_echiquier"])->getValue() ?? 30);
        $countMaxBetweenSpecial = (float)($paramRepository->findOneBy(["Name"=>"ratio_special"])->getValue() ?? 40);

        $timeZone = new DateTimeZone('Europe/Paris');

        $debut_tournoi = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." ".$paramRepository->findOneBy(["Name"=>"time_debut"])->getValue().":00");
        $time = $debut_tournoi;
        $tpsMatch = (float)($paramRepository->findOneBy(["Name"=>"tps_match"])->getValue() ?? 12);
        $tpsPause = (float)($paramRepository->findOneBy(["Name"=>"tps_pause"])->getValue() ?? 12);
        $entreMatch = $tpsMatch + $tpsPause;
        $fields = $fieldRepository->findAll();

        $phasesEchiquier = $phaseRepository->findEchiquier();
        $phasesSpecial = $phaseRepository->findBy(["param"=>"exception"]);

        $tourSpeciaux = [];

        foreach ($phasesSpecial as $key => $ts)
        {
            $tourSpeciaux[$ts->getId()] = 1;
        }

        $tourEchiquier = [];
        foreach ($phasesEchiquier as $key => $pe)
        {
            $tourEchiquier[$pe->getId()] = 1;
        }
        $lastTour = false;
        $lastPhase = false;
        $matchEntreEchiqiuer = 0;
        $matchEntreSpecial = 0;
        $matchClassement = false;
        $coef = 1;
        $countField = count($fields);
        $fieldToPlace = 0;

        $minTerrain56 = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 10:15:00");
        $maxTerrain56 = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 14:15:00");

        $minMidi = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 11:30:00");
        $maxMidi = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 12:30:00");

        $times = [];

        //phase 1 : Phase 1 - U13
        //phase 2 : Phase 2 - U13
        //phase 3 : Matchs de classement U13
        //phase 5 : Phase 1 - U11
        //phase 6 : Phase 2 - U11
        //phase 7 : Matchs de classement U11
        //phase 8 : Tournoi - U13F

        $times[] = ["tour"=>1,"phase"=>"5"]; //12 matchs U11
        $times[] = ["tour"=>1,"phase"=>"1"]; //16 matchs  U13
        $times[] = ["tour"=>1,"phase"=>"8"]; //3 matchs U13F


        $times[] = ["tour"=>2,"phase"=>"5"]; //U11
        $times[] = ["tour"=>2,"phase"=>"1"]; //U13
        $times[] = ["tour"=>2,"phase"=>"8"]; //U13F


        $times[] = ["tour"=>3,"phase"=>"5"]; //U11
        $times[] = ["tour"=>3,"phase"=>"1"]; //U13
        $times[] = ["tour"=>3,"phase"=>"8","pauseAfert"=>true]; //U13F

        $times[] = ["tour"=>1,"phase"=>"6"]; //U11
        $times[] = ["tour"=>1,"phase"=>"2"]; //U13

        $times[] = ["tour"=>2,"phase"=>"6"];
        $times[] = ["tour"=>2,"phase"=>"2"];
        $times[] = ["tour"=>4,"phase"=>"8"];


        $times[] = ["tour"=>3,"phase"=>"6"];
        $times[] = ["tour"=>3,"phase"=>"2"];
        $times[] = ["tour"=>5,"phase"=>"8"];

        //pauseAfert
        $times[] = ["tour"=>1,"phase"=>"7"];
        $times[] = ["tour"=>1,"phase"=>"3"];
        $times[] = ["tour"=>6,"phase"=>"8"];

        $times[] = ["tour"=>2,"phase"=>"7"];
        $times[] = ["tour"=>2,"phase"=>"3"];

        $times[] = ["tour"=>3,"phase"=>"7"];
        $times[] = ["tour"=>3,"phase"=>"3"];



        foreach($times as $timeToFind) {

            $matchs = $meetRepository->findBy(["Phase"=>$timeToFind["phase"],"Tour"=>$timeToFind["tour"]]);

            foreach ($matchs as $match) {
                if ($match->getName()) {
                    $matchClassement = true;

                }

                $field = $fields[$fieldToPlace];

                $lastMatchA = $lastMatchB = false;

                while (!
                (
                    ($field->getId() != 5 && $field->getId() != 6 && $field->getId() != 7 && $field->getId() != 3)
                    ||
                    (($field->getId() == 5 || $field->getId() == 6) && ($time->getTimestamp() <= $minTerrain56->getTimestamp() || $time->getTimestamp() >= $maxTerrain56->getTimestamp()))
                    ||
                    (($field->getId() == 7 || $field->getId() == 3 ) && (!$matchClassement)  && ( $time->getTimestamp() <= $minMidi->getTimestamp() || $time->getTimestamp() >= $maxMidi->getTimestamp())  )

                    //10 enlever à la fin
                    //temps de pause +1 minutes
                    //17h20
                )


                ) {
                    $fieldToPlace++;
                    if ($fieldToPlace >= $countField) {
                        $fieldToPlace = 0;
                        $time->add((DateInterval::createFromDateString($entreMatch . " minutes")));
                    }
                    $field = $fields[$fieldToPlace];

                }



                        $match->setTime($time->getTimestamp());
                        $match->setField($field);
                        $entityManager->persist($match);
                        $entityManager->flush();
                        $fieldToPlace++;

                        if ($fieldToPlace >= $countField) {
                            $fieldToPlace = 0;
                            $time->add((DateInterval::createFromDateString($entreMatch . " minutes")));


                        }



            }
            if(isset($timeToFind["pauseAfert"]))
            {

                $time->add((DateInterval::createFromDateString("22 minutes")));
            }

        }

        return $this->redirectToRoute("teams_index_admin");
    }

    /**
     * @Route("/admin/times", name="time_index")
     */
    public function times_index(Request $request, PhaseRepository $phaseRepository, TeamRepository $teamRepository, FieldRepository $fieldRepository, MeetRepository $meetRepository, CategoryRepository $categoryRepository): Response
    {
        $parameters = [];
        $filterPhase = $request->get("filterPhase");
        if($filterPhase)
        {
            $parameters["Phase"] = $filterPhase;
        }

        $filterField = $request->get("filterField");
        if($filterField)
        {
            $parameters["Field"] = $filterField;
        }

        $data = $meetRepository->findBy($parameters,["time"=>"ASC"]);



        $filterTeam = $request->get("filterTeam");
        if($filterTeam)
        {
            $data = array_filter($data,function($m) use($filterTeam){  return ($m->getTeamA() && $m->getTeamA()->getId() == $filterTeam) || ($m->getTeamB() && $m->getTeamB()->getId() == $filterTeam)  ;});

        }

        $filterCategorie = $request->get("filterCategorie");
        if($filterCategorie)
        {
            $data = array_filter($data,function($m) use($filterCategorie){  return ($m->getPhase()->getCategory()->getId() == $filterCategorie)  ;});

        }

        return $this->render("admin/times/index.html.twig", [
            "filterTeam"=>$filterTeam,
            "filterCategorie" =>$filterCategorie,
            "teams"=>$teamRepository->findAll(),
            "filterField"=>$filterField,
            "fields"=>$fieldRepository->findAll(),
            "Phases"=>$phaseRepository->findAll(),
            "filterPhase"=>$filterPhase,
            "phases"=>$phaseRepository->findAll(),
            "categories"=>$categoryRepository->findAll(),
            "times"=>$data
        ]);

    }
}
