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
    public function index(MeetRepository $meetRepository, ParamRepository $paramRepository, PhaseRepository $phaseRepository, FieldRepository $fieldRepository, EntityManagerInterface $entityManager): Response
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

            $ordres = [1, 2, 3];
            foreach ($ordres as $ordre) {
                $matchs = $meetRepository->findBySpecial($ordre);

                while (count($matchs) > 0) {

                    if(isset($matchs[0]) && $matchs[0]->getName())
                    {
                        $matchClassement = true;

                    }

                    if($matchEntreSpecial === 0 || $matchEntreSpecial > $countMaxBetweenSpecial )
                    {
                        foreach ($phasesSpecial as $pe) {

                            $matchs_special = $meetRepository->findBy(["Phase" => $pe->getId(), "Tour" => $tourSpeciaux[$pe->getId()]]);
                            $tourSpeciaux[$pe->getId()]++;
                            $matchs = array_merge($matchs_special, $matchs);
                            $matchEntreSpecial = 1;
                        }


                    }

                    if($matchEntreEchiqiuer === 0 || $matchEntreEchiqiuer > $countMaxBetween )
                    {
                            foreach ($phasesEchiquier as $pe) {

                                $matchs_echiquier = $meetRepository->findBy(["Phase" => $pe->getId(), "Tour" => $tourEchiquier[$pe->getId()]]);
                                $tourEchiquier[$pe->getId()]++;
                                $matchs = array_merge($matchs_echiquier, $matchs);
                                $matchEntreEchiqiuer = 1;
                            }


                    }
                    shuffle($fields);
                    foreach ($fields as $field) {

                        $min = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 10:15:00");
                        $max = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 12:15:00");
                        $pauseMidiDebut = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 12:30:00");
                        $pauseMidiFin = new DateTime($paramRepository->findOneBy(["Name"=>"date_debut"])->getValue()." 13:00:00");



                        if(
                        (
                            ( $field->getId() != 5 && $field->getId() != 6  && $field->getId() != 7 )
                            ||
                            ( ($field->getId() == 5 || $field->getId() == 6 ) && (  $time->getTimestamp() <= $min->getTimestamp() || $time->getTimestamp() >= $max->getTimestamp()  )  )
                            ||
                            ( ($field->getId() == 7 ) && (  !$matchClassement  )  )
                        )
                        &&
                        (
                            $time->getTimestamp() <= $pauseMidiDebut->getTimestamp() || $time->getTimestamp() >= $pauseMidiFin->getTimestamp()
                        )
                        && isset($matchs[0]) )

                        {
                                    $lastTour = $matchs[0]->getTour();
                                    $lastPhase = $matchs[0]->getPhase();
                                    $matchs[0]->setTime($time->getTimestamp());
                                    $matchs[0]->setField($field);
                                    $entityManager->persist($matchs[0]);
                                    array_splice($matchs, 0, 1);
                                    $matchEntreEchiqiuer++;
                                    $matchEntreSpecial++;
                                }

                    }

                    $time->add((DateInterval::createFromDateString($entreMatch . " minutes")));


                }
                $entityManager->flush();
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
