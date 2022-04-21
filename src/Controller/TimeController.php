<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\FieldRepository;
use App\Repository\MeetRepository;
use App\Repository\PhaseRepository;
use function array_merge;
use function array_splice;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimeController extends AbstractController
{
    /**
     * @Route("/generate/time", name="app_generate_time")
     */
    public function index(MeetRepository $meetRepository, PhaseRepository $phaseRepository, FieldRepository $fieldRepository, EntityManagerInterface $entityManager): Response
    {
        //supprime les anciens horaires
        foreach($meetRepository->findAll() as $match)
        {
            $match->setTime(null);
            $entityManager->persist($match);

        }
        $entityManager->flush();
        $timeZone = new DateTimeZone('Europe/Paris');

        $debut_tournoi = new DateTime("2022-05-27 09:00:00");
        $time = $debut_tournoi;
        $tpsMatch = 10;
        $tpsPause = 2;
        $entreMatch = $tpsMatch + $tpsPause;
        $fields = $fieldRepository->findAll();

        $phasesEchiquier = $phaseRepository->findEchiquier();

        $tourEchiquier = [];

        foreach ($phasesEchiquier as $pe)
        {
            $tourEchiquier[$pe->getId()] = 1;
        }
        $lastTour = false;

            $ordres = [1, 2, 3];
            foreach ($ordres as $ordre) {
                $matchs = $meetRepository->findBySpecial($ordre);

                while (count($matchs) > 0) {

                    if(!$lastTour || (isset($matchs[0]) && $matchs[0]->getPhase()->getType()->getFormat() !== "echiquier" &&  $lastTour !== $matchs[0]->getTour()))
                    {

                            foreach ($phasesEchiquier as $pe) {

                                $matchs_echiquier = $meetRepository->findBy(["Phase" => $pe->getId(), "Tour" => $tourEchiquier[$pe->getId()]]);
                                $tourEchiquier[$pe->getId()]++;
                                $matchs = array_merge($matchs_echiquier, $matchs);
                            }


                    }

                    foreach ($fields as $field) {

                        if (isset($matchs[0])) {
                            $lastTour = $matchs[0]->getTour();
                            $matchs[0]->setTime($time->getTimestamp());
                            $matchs[0]->setField($field);
                            $entityManager->persist($matchs[0]);
                            array_splice($matchs, 0, 1);
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
    public function times_index(MeetRepository $meetRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render("admin/times/index.html.twig", ["categories"=>$categoryRepository->findAll(),"times"=>$meetRepository->findBy([],["time"=>"ASC"])]);

    }
}
