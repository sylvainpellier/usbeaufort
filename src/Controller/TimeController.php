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
        $fieldToPlace = 1;

        $min = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 10:15:00");
        $max = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 12:15:00");

        $times = [];

        $times[] = ["tour"=>1,"category"=>1,"phase"=>"5"];
        $times[] = ["tour"=>1,"category"=>3,"phase"=>"8"];

        $times[] = ["tour"=>1,"category"=>2,"phase"=>"1"];

        $times[] = ["tour"=>2,"category"=>3,"phase"=>"8"];

        $times[] = ["tour"=>2,"category"=>1,"phase"=>"1"];
        $times[] = ["tour"=>2,"category"=>2,"phase"=>"5"];

        $times[] = ["tour"=>3,"category"=>3,"phase"=>"8"];

        $times[] = ["tour"=>3,"category"=>1,"phase"=>"1"];
        $times[] = ["tour"=>3,"category"=>2,"phase"=>"5"];

        $times[] = ["tour"=>4,"category"=>3,"phase"=>"8"];

        $times[] = ["tour"=>1,"category"=>1,"phase"=>"2"];
        $times[] = ["tour"=>1,"category"=>2,"phase"=>"6"];

        $times[] = ["tour"=>5,"category"=>3,"phase"=>"8"];

        $times[] = ["tour"=>2,"category"=>1,"phase"=>"2"];
        $times[] = ["tour"=>2,"category"=>2,"phase"=>"6"];

        $times[] = ["tour"=>6,"category"=>3,"phase"=>"8"];

        $times[] = ["tour"=>3,"category"=>1,"phase"=>"2"];
        $times[] = ["tour"=>3,"category"=>2,"phase"=>"6"];

        $times[] = ["tour"=>1,"category"=>1,"phase"=>"3"];
        $times[] = ["tour"=>1,"category"=>2,"phase"=>"7"];

        $times[] = ["tour"=>2,"category"=>1,"phase"=>"3"];
        $times[] = ["tour"=>2,"category"=>2,"phase"=>"7"];

        $times[] = ["tour"=>3,"category"=>1,"phase"=>"3"];
        $times[] = ["tour"=>3,"category"=>2,"phase"=>"7"];

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
                    ($field->getId() != 5 && $field->getId() != 6 && $field->getId() != 7)
                    ||
                    (($field->getId() == 5 || $field->getId() == 6) && ($time->getTimestamp() <= $min->getTimestamp() || $time->getTimestamp() >= $max->getTimestamp()))
                    ||
                    (($field->getId() == 7) && (!$matchClassement))
                )


                ) {
                    $fieldToPlace++;
                    if ($fieldToPlace >= $countField) { $fieldToPlace = 1; }
                    $field = $fields[$fieldToPlace];

                }



                        $match->setTime($time->getTimestamp());
                        $match->setField($field);
                        $entityManager->persist($match);
                        $entityManager->flush();
                        array_splice($matchs, 0, 1);
                        $fieldToPlace++;

                        if ($fieldToPlace >= $countField) {
                            $fieldToPlace = 1;
                            $time->add((DateInterval::createFromDateString($entreMatch . " minutes")));

                        }



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
