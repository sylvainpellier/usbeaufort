<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\Phase;
use App\Entity\Position;
use App\Entity\Poule;
use App\Entity\Team;
use App\Form\PhaseType;
use App\Repository\CategoryRepository;
use App\Repository\MeetRepository;
use App\Repository\PhaseRepository;
use App\Repository\PositionRepository;
use App\Repository\PouleRepository;
use App\Repository\TeamRepository;
use function array_key_exists;
use function array_push;
use function array_search;
use function array_splice;
use function d;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function shuffle;
use function str_contains;
use function str_replace;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function var_dump;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }


    /**
     * @Route("/admin/phase/add/{idCategory}", name="app_admin_add_phase")
     */
    public function app_admin_add_phase( string $idCategory, Request $request, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {
        $phase = new Phase();
        $phase->setCategory($categoryRepository->find($idCategory));
        $form = $this->createForm(PhaseType::class,$phase);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $phase = $form->getData();
            $entityManager->persist($phase);
            $entityManager->flush();
            $this->addFlash("success","Phase ajoutée avec succès");
            return $this->redirectToRoute("app_admin_category_phase",["idCategory"=>$phase->getCategory()->getId(),"idPhase"=>$phase->getId()]);
        }

        return $this->render('admin/phase/update.html.twig', [
            'form' => $form->createView(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }


    /**
     * @Route("/admin/phase/{id}", name="app_admin_update_phase")
     */
    public function app_admin_update_phase(string $id, Request $request, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {
        $phase = $phaseRepository->find($id);
        $form = $this->createForm(PhaseType::class, $phase);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $phase = $form->getData();
            $entityManager->persist($phase);
            $entityManager->flush();
            $this->addFlash("success","Phase modifiée avec succès");
            return $this->redirectToRoute("app_admin_category_phase",["idCategory"=>$phase->getCategory()->getId(),"idPhase"=>$phase->getId()]);
        }

        return $this->render('admin/phase/update.html.twig', [
            'form' => $form->createView(),
            'phase' => $phase,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/category/{id}", name="app_admin_category")
     */
    public function category(string $id, CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category.html.twig', [
            'category' => $categoryRepository->find($id),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}", name="app_admin_category_phase")
     */
    public function categoryphase(string $idCategory, string $idPhase, MeetRepository $meetRepository, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $c = new ApiController($entityManager);
        $phase = $phaseRepository->find($idPhase);

        return $this->render('admin/category_phase.html.twig', [
            'category' => $categoryRepository->find($idCategory),
            'phase' => $phase,
            'groupes' => $meetRepository->findGroupes($idCategory,$idPhase),
            'categories' => $categoryRepository->findAll(),
            'classement' =>$c->data($idCategory,$phase, $conn = $entityManager->getConnection(),false)
        ]);
    }


    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/delete", name="app_admin_category_phase_delete")
     */
    public function category_phase_delete(string $idCategory, TeamRepository $teamRepository, MeetRepository $meetRepository, string $idPhase, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {
        $phase = $phaseRepository->find($idPhase);
        $matchs = $meetRepository->findBy(["Phase"=>$phase]);


        //On supprime tous les matchs de la phase
        foreach ($matchs as $match)
        {
            $entityManager->remove($match);
            $entityManager->flush();
        }



        return $this->redirectToRoute("app_admin_category_phase", ["idCategory"=>$idCategory,"idPhase"=>$idPhase]);


    }



function findTeamByRang($teams,$rang)
{
    foreach($teams as $team)
    {
        if($team['rang'] == $rang) return $team;
    }
}

    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/simulate", name="app_admin_simulate_match")
     */
    public function app_admin_simulate_match(string $idCategory, PouleRepository $pouleRepository, PositionRepository $positionRepository, TeamRepository $teamRepository, MeetRepository $meetRepository, string $idPhase, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {
        $conn = $entityManager->getConnection();
        $sql = " UPDATE usb_meets SET score_a = FLOOR( 1 + RAND( ) *3 ) , score_b = FLOOR( 1 + RAND( ) *3 ) WHERE phase_id = :phase ";
        $stmt = $conn->prepare($sql);
        $parameters = ["phase"=>$idPhase];
        $stmt->executeQuery($parameters);

        $conn = $entityManager->getConnection();
        $sql = " UPDATE usb_meets SET penalty_a = FLOOR( 1 + RAND( ) *5 ) , penalty_b = FLOOR( 1 + RAND( ) *5 ) WHERE score_a = score_b AND phase_id = :phase";
        $stmt = $conn->prepare($sql);
        $parameters = ["phase"=>$idPhase];
        $stmt->executeQuery($parameters);

        $this->addFlash("success","Score simulé avec succès");

        return $this->redirectToRoute("app_admin_category_phase",["idPhase"=>$idPhase,"idCategory"=>$idCategory]);


        ;

    }

    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/generate", name="app_admin_category_phase_generate")
     */
    public function generatePhase(string $idCategory, PouleRepository $pouleRepository, PositionRepository $positionRepository, TeamRepository $teamRepository, MeetRepository $meetRepository, string $idPhase, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $phase = $phaseRepository->find($idPhase);
        $phase = $phase->getPhaseSuivante() ?  $phase->getPhaseSuivante() : $phase;


        if($phase->getType()->getFormat() === "principal-consolante" || $phase->getType()->getFormat()=== "demifinalesfinales")
        {

        $c = new ApiController($entityManager);
        $data = $c->data($idCategory,$phase->getPhasePrecedente(), $conn = $entityManager->getConnection());

        if($phase->getParam() == 24) {
            foreach ($data as $keyGroupe => $teamsGroupe) {
                foreach ($teamsGroupe as $key => $team) {

                    $wait = [];
                    if ($team["rang"] == 3 && $phase->getParam() == 24) {
                        $wait[$key] = $team;
                    }
                }

            }

            //on tri les troisièmes
            usort($wait, function ($a,$b) {

                //PRENDRE EN COMPTE LES ÉGALITÉS
                //DIVERSES VOIR LE RELGEMENT
                //TODO : classement
                return $a['pts']<$b['pts'];
            });

            $rang = 1;
            foreach($wait as $key => $twait)
            {
                if($rang <= 4)
                { $r= 3; } else { $r = 4; }
                    foreach ($data as $keyGroupe => $teamsGroupe) {
                        foreach ($teamsGroupe as $keyG => $team) {
                            if($team["id"] === $twait["id"])
                            {
                                $data[$keyGroupe][$keyG]["rang"] = $r;
                            }
                        }
                    }

                $rang++;
            }
        }



        foreach($data as $keyGroupe => $teamsGroupe)
        {
            foreach ($teamsGroupe as $team)
            {
                $t = $teamRepository->find($team["id"]);

                $position = $positionRepository->findOneBy(["PouleFrom"=> $team["poule"], "Rang"=> $team["rang"]]);

                if($position)
                {
                    $t->addPoule( $pouleRepository->find( $position->getPouleTo()) );
                    $entityManager->persist($position);
                    $entityManager->persist($t);
                    $entityManager->flush();

                    $meets = $meetRepository->findBy(["PositionA"=>$position]);
                    foreach ($meets as $meet)
                    {
                        $meet->setTeamA($t);
                        $entityManager->persist($meet);
                    }

                    $meets = $meetRepository->findBy(["PositionB"=>$position]);
                    foreach ($meets as $meet)
                    {
                        $meet->setTeamB($t);
                        $entityManager->persist($meet);
                    }
                    $entityManager->flush();

                }
            }
        }




            $entityManager->flush();




    }
        else if($phase->getType()->getFormat() === "echiquier")
    {

        $c = new ApiController($entityManager);
        $data = $c->data($idCategory,$phase, $conn = $entityManager->getConnection());


        $poule = $pouleRepository->findOneBy(["Phase"=>$phase]);
        $teams = $poule->getTeams();

        $tour = $meetRepository->findOneBy(["Phase"=>$phase,"TeamA"=>null,"TeamB"=>null],["Tour"=>"ASC"])->getTour();
        foreach($data as $keyGroupe => $teamsGroupe)
        {
            foreach ($teamsGroupe as $key => $team)
            {

                $matchTourA = $meetRepository->findBy(["Phase"=>$phase,"Tour"=>$tour,"TeamA"=>$team["id"]]);
                $matchTourB = $meetRepository->findBy(["Phase"=>$phase,"Tour"=>$tour,"TeamB"=>$team["id"]]);

                if(count($matchTourA) === 0 && count($matchTourB) === 0) {
                    $match = $meetRepository->findOneBy(["Tour" => $tour, "Phase" => $phase, "TeamA" => null, "TeamB" => null]);
                    if ($match) {
                        $teamA = $teamRepository->find($team["id"]);
                        $match->setTeamA($teamA);
                        $addRang = 1;
                        $find = true;
                        do {
                            $against = $this->findTeamByRang($teamsGroupe, $team["rang"] + $addRang);
                            if ($against !== $teamA) {

                                $matchTourA = $meetRepository->findBy(["Phase"=>$phase,"TeamA"=>$team["id"],"TeamB"=>$against]);
                                $matchTourB = $meetRepository->findBy(["Phase"=>$phase,"TeamB"=>$team["id"],"TeamA"=>$against]);

                                if(count($matchTourA) > 0 || count($matchTourB) > 0) {
                                    $find = true;
                                } else
                                {
                                    $find = false;
                                }

                                if (!$find) {
                                    $match->setTeamB($teamRepository->find($against["id"]));
                                }

                            }
                            $addRang++;
                        } while ($find);
                        $entityManager->persist($match);
                        $entityManager->flush();
                    }

                }

            }
        }





        $entityManager->flush();




    }





        return $this->redirectToRoute("app_admin_category_phase", ["idCategory"=>$idCategory,"idPhase"=>$phase->getId()]);
    }

    public function findCorrectTour(&$tours, $teamA, $teamB)
    {

        foreach($tours as $key => &$tour)
        {
            if(count($tour) === 0)
            {
                return $key;
            } else {
                foreach ($tour as $matchDansTour) {

                    if ($matchDansTour["a"] !== $teamA && $matchDansTour["b"] !== $teamB && $matchDansTour["a"] !== $teamB && $matchDansTour["b"] !== $teamA) {
                        return $key;
                    } else
                    {

                    }
                }
            }
        }

    }

    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/groupe/{groupe}", name="app_admin_category_phase_groupe")
     */
    public function categoryphasgroupe(string $idCategory, PouleRepository $pouleRepository, string $idPhase, string $groupe, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $c = new ApiController($entityManager);
        $phase = $phaseRepository->find($idPhase);
        return $this->render('admin/category_phase_groupe_matchs.html.twig', [
            'category' => $categoryRepository->find($idCategory),
            'phase' => $phase,
            'groupe' => $groupe,
            'poule' => $pouleRepository->find($groupe),
            'categories' => $categoryRepository->findAll(),
            'classement' =>$c->data($idCategory,$phase, $conn = $entityManager->getConnection(), $groupe)
        ]);
    }

    /**
     * @Route("/admin/category/{idCategory}/generate", name="app_admin_category_generate")
     */
    public function generateFictive(string $idCategory, PositionRepository $positionRepository, PouleRepository $pouleRepository, TeamRepository $teamRepository, MeetRepository $meetRepository, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $alphas = range('A', 'Z');
        $category = $categoryRepository->find($idCategory);
        $phases = $category->getPhases();
        $teams = $teamRepository->findBy(["Category"=>$category], ["Rang"=>"ASC"]);

        foreach ($phases as $phase)
        {
            //On supprime les matchs existant
            $matchs = $meetRepository->findBy(["Phase"=>$phase]);

            //On supprime tous les matchs de la phase
            foreach ($matchs as $match)
            {
                $entityManager->remove($match);
            }


            foreach($phase->getPoules() as $poule)
            {
                $entityManager->remove($poule);
            }

            $entityManager->flush();


            $poules = [];
            $nbTeamByPoule = $phase->getType()->getTeamByPoule();
            $count3=1;

            if($phase->getType()->getFormat() === "normal")
            {
                for($i = 0; $i <= (count($teams) / $nbTeamByPoule) - 1 ; $i++)
                {
                    $poule = new Poule();
                    $poule->setName($alphas[$i]);
                    $poule->setPhase($phase);
                    $entityManager->persist($poule);
                    $poules[] = $poule;

                        for($rang=1;$rang<=$nbTeamByPoule;$rang++)
                        {
                                $position = new Position();
                                $position->setPouleFrom($poule);
                                $position->setRang($rang);
                                if($phase->getParam() == 24 && $rang === 3)
                                {
                                    $position->setIntParam( $count3 );
                                    $count3++;
                                }
                                $position->setPhaseFrom($phase);
                                $entityManager->persist($position);
                                $entityManager->persist($poule);


                        }
                    }


                $entityManager->flush();


                //premier parcours pour les équipes classés
                foreach ($teams as $team)
                {
                    if($team->getRang())
                    {
                        foreach($poules as $poule)
                        {
                            $find = false;
                            foreach ($poule->getTeams() as $t)
                            {
                                if($t->getRang() === $team->getRang())
                                {
                                    $find = true;
                                }
                            }
                            if(!$find)
                            {
                                $poule->addTeam($team);
                                break;
                            }

                        }
                        $entityManager->persist($team);
                    }
                }


                $entityManager->flush();

                //deuxième parcour pour les équipes non classés
                foreach ($teams as $team)
                {

                    if(!$team->getRang()) {
                        //random
                        foreach ($poules as $poule)
                        {

                            if( count($poule->getTeams()) < $nbTeamByPoule)
                            {
                                $poule->addTeam($team);
                                $entityManager->persist($poule);
                                $entityManager->flush();
                                break;
                            }
                        }
                    }
                }


               $this->genereMatch($poules,$phase,$entityManager);

            }
            else if($phase->getType()->getFormat() === "principal-consolante") {

                for($i = 0; $i <= (count($teams) / $nbTeamByPoule) - 1 ; $i++)
                {

                    $poule = new Poule();
                    $poule->setPhase($phase);


                    if( ($phase->getParam() == 32 && $i< (count($teams) / $nbTeamByPoule) / 2) || ($phase->getParam() == 24 && $i < 4))
                    {
                        $poule->setPrincipal(true);
                        $poule->setName($alphas[$i]. " - Principale");

                    } else
                    {
                        $poule->setPrincipal(false);
                        $poule->setName($alphas[$i]." - Consolante");
                    }


                    for($rang=1;$rang<=4;$rang++) {
                        $position = new Position();
                        $position->setPouleFrom($poule);
                        $position->setPhaseFrom($phase);
                        $position->setPrincipal($poule->getPrincipal());

                        if($phase->getParam() == 24 && !$poule->getPrincipal())
                        {
                            if($rang <= 2)  $position->setIdString( "1_2" ); else  $position->setIdString( "3_4" );

                            $count3++;
                        }  $position->setRang($rang);
                        $entityManager->persist($position);
                   }



                    $entityManager->persist($poule);
                    $entityManager->flush();
                    $poules[] = $poule;
                }

                while(count($positionRepository->findBy([ "PhaseFrom"=>$phase->getPhasePrecedente(), "PouleTo" => null])) > 0)
                {
                    $conn = $entityManager->getConnection();
                    shuffle($poules);
                    foreach ($poules as $poule) {

                        for ($rang = 1; $rang <= $nbTeamByPoule; $rang++) {

                            //if ($phase->getParam() == 32 || ($phase->getParam() == 24 && $rang != 3)) {
                            $parameters = [];
                                $sql = "SELECT * FROM usb_positions p WHERE p.phase_from_id = :phase AND 1 = 1 ";
                                $parameters["phase"] = $phase->getPhasePrecedente()->getId();
                                if ($phase->getParam() == 32)
                                {
                                    $sql .= " AND p.poule_from_id NOT IN ( SELECT p2.poule_from_id FROM usb_positions p2 WHERE p2.poule_to_id = :poule ) ";
                                    $sql .= " AND p.rang != :rang ";
                                    $parameters["rang"] = $rang;
                                    $parameters["poule"] = $poule->getId();

                                }
                                if ($phase->getParam() == 32)
                                {
                                    $sql .= ($poule->getPrincipal()) ? " AND p.rang <= 2" : " AND p.rang > 2";
                                } else
                                {
                                    $sql .= ($poule->getPrincipal()) ? " AND ( p.rang <= 2 OR ( p.rang = 3 AND int_param <= 4)) " : " AND p.rang >= 4 OR (p.rang = 3 AND int_param >= 5)  ";
                                }
                                $sql .= " AND p.poule_to_id IS  NULL";


                                $stmt = $conn->prepare($sql);
                                $resultSet = $stmt->executeQuery($parameters);
                                $positionsPossibles = $resultSet->fetchAll();

                                shuffle($positionsPossibles);
                                $find = false;

                                foreach ($positionsPossibles as $positionTo) {
                                    if (count($positionRepository->findBy(["PouleFrom" => $positionTo["poule_from_id"], "PouleTo" => $poule])) === 0) {
                                        if (count($positionRepository->check($poule->getId(), $rang)) <= 1) {
                                            if (count($positionRepository->check($poule->getId())) < 4) {
                                                $find = $positionTo;
                                                break;
                                            }
                                        }
                                    }
                                }


                                if ($find) {

                                    $poule->addPositionsTo($positionRepository->find($find["id"]));
                                    $entityManager->persist($poule);
                                    $entityManager->flush();
                                }





                        }
                    }


                    }

//                    $perfect = true;
//                    while($perfect)
//                    {
//
//                        $perfect = true;
//                        foreach ($poules as $poule) {
//                            $positions = $positionRepository->findBy(["PouleTo" => $poule]);
//                            if(count($positions) !== $nbTeamByPoule)
//                            {
//                                $perfect = false;
//                                if(count($positions) > $nbTeamByPoule)
//                                {
//                                    $positions[0]->setPouleTo(null);
//                                } else {
//
//                                    $positionsSearch = $positionRepository->findBy(["PhaseFrom" => $phase->getId(), "Principal"=>$poule->getPrincipal() ]);
//
//                                    if(count($positionsSearch) > 0)
//                                    {
//                                        $positionsSearch[0]->setPouleTo($poule);
//                                    }
//                                }
//                            }
//                        }
//                    }


                foreach ($poules as $poule) {

                    $tours = [];
                    for ($i = 1; $i <= $nbTeamByPoule - 1; $i++) {
                        $tours[$i] = [];
                    }

                    $meetsPoule = [];
                    $positionsA = $positionRepository->findBy(["PouleTo" => $poule]);


                    foreach ($positionsA as $pA) {
                        $positionsB = $positionRepository->findBy(["PouleTo" => $poule]);

                        foreach ($positionsB as $pB) {
                            $find = false;


                            foreach ($meetsPoule as $meet2) {
                                if (($meet2->getPositionA() === $pA && $meet2->getPositionB() === $pB) || ($meet2->getPositionA() === $pB && $meet2->getPositionB() === $pA)) {
                                    $find = true;

                                }
                            }

                            if ($pA !== $pB && !$find) {

                                $match = new Meet();
                                $match->setPoule($poule);
                                $match->setPositionA($pA);
                                $match->setPositionB($pB);
                                $match->setPrincipal($poule->getPrincipal());
                                $match->setPhase($phase);
                                $tour = $this->findCorrectTour($tours, $pA,$pB);
                                $match->setTour($tour);
                                $entityManager->persist($match);
                                $entityManager->flush();
                                $meetsPoule[] = $match;
                                $tours[$tour][] = ["a"=>$pA,"b"=>$pB];
                            }
                        }
                    }
                }








                }
            else if($phase->getType()->getFormat() === "demifinalesfinales") {


                $poules = [];
                $x = 1;
                $principal = true;
                for($i = 0; $i <= (count($teams) / $nbTeamByPoule) -1 ; $i++)
                {
                    if($i===4) { $x = 1; $principal = false;}
                    $poule = new Poule();
                    $poule->setPhase($phase);
                    $poule->setPrincipal($principal);
                    if($phase->getParam() == 32 || $principal)
                    {
                        $str = ($x<2)?"er":"ème";
                        $str2 = ($principal) ? "principales" : "consolantes";
                        $poule->setName($alphas[$i]. " - ". $x."".$str." des poules $str2 de phase 2");
                    } else if( $phase->getParam() == 24)
                    {
                        $str2 = ($principal) ? "principales" : "consolantes";
                        if($i===4)
                        {
                            $poule->setName($alphas[$i]. " - 1er et 2ème des poules $str2 de phase 2");
                        } else
                        {
                            $poule->setName($alphas[$i]. " - 3ème et 4ème des poules $str2 de phase 2");
                        }



                    }


                    $entityManager->persist($poule);
                    $poules[] = $poule;

                    if($phase->getPhasePrecedente()->getParam() == 24 && !$poule->getPrincipal())
                    {
                        $id_string = ($i===4) ? "1_2" : "3_4";
                        $positionsPrecedentes = $positionRepository->findBy(["PouleTo"=>null,"id_string"=> $id_string,"Principal"=>$poule->getPrincipal() ,"PhaseFrom"=>$phase->getPhasePrecedente()]);
                    } else
                    {
                        $positionsPrecedentes = $positionRepository->findBy(["PouleTo"=>null,"Rang"=>$x,"Principal"=>$poule->getPrincipal() ,"PhaseFrom"=>$phase->getPhasePrecedente()]);

                    }

                    foreach ($positionsPrecedentes as $pp)
                    {
                        $pp->setPouleTo($poule);
                        $entityManager->persist($pp);
                        $entityManager->flush();
                    }

                    $x++;


                }
                $entityManager->flush();


                //on créé les matchs
                foreach ($poules as $poule) {
                    $positions = $positionRepository->findBy(["PouleTo" => $poule]);


                    if(count($positions) === 4) {
                        shuffle($positions);

                        $match = new Meet();
                        $match->setPoule($poule);
                        $match->setPositionA($positions[0]);
                        $match->setPositionB($positions[1]);
                        $match->setName("Demie Finale - 1");
                        $match->setPrincipal($poule->getPrincipal());
                        $match->setPhase($phase);
                        $entityManager->persist($match);
                        $entityManager->flush();

                        $match = new Meet();
                        $match->setPoule($poule);
                        $match->setPositionA($positions[2]);
                        $match->setPositionB($positions[3]);
                        $match->setName("Demie Finale - 2");
                        $match->setPrincipal($poule->getPrincipal());
                        $match->setPhase($phase);
                        $entityManager->persist($match);
                        $entityManager->flush();

                        $match = new Meet();
                        $match->setPoule($poule);
                        $match->setName("Finale des perdants");
                        $match->setPrincipal($poule->getPrincipal());
                        $match->setPhase($phase);
                        $entityManager->persist($match);
                        $entityManager->flush();

                        $match = new Meet();
                        $match->setPoule($poule);
                        $match->setName("Finale des vainqueurs");
                        $match->setPrincipal($poule->getPrincipal());
                        $match->setPhase($phase);
                        $entityManager->persist($match);
                        $entityManager->flush();


                    }



                            }

                            $entityManager->flush();

                }
            else if($phase->getType()->getFormat() === "echiquier")
            {
                $tour = 1;
                $poule = new Poule();
                $poule->setPhase($phase);
                $poule->setName("Poule Échiquier");
                $entityManager->persist($poule);
                shuffle($teams);
                $against = null;
                foreach ($teams as $team)
                {
                    $team->addPoule($poule);
                    $entityManager->persist($team);
                    if(!$against)
                    {
                        $against = $team;
                    } else
                    {
                        $match = new Meet();
                        $match->setTeamA($team);
                        $match->setTeamB($against);
                        $match->setPhase($phase);
                        $match->setPoule($poule);
                        $match->setTour($tour);
                        $entityManager->persist($match);
                        $against = null;
                    }
                }

                //TODO : si impair, une équipe ne joue pas, gestion des points
                $entityManager->flush();

                for($i=0;$i<=$phase->getParam()-2;$i++)
                {
                    $tour++;
                    for($j=0;$j<(count($teams) - 1) / 2;$j++) {
                        $match = new Meet();
                        $match->setName("à déterminer");
                        $match->setPhase($phase);
                        $match->setPoule($poule);
                        $match->setTour($tour);
                        $entityManager->persist($match);
                        $against = null;
                    }
                }

            }







            }



            $entityManager->flush();

        return $this->redirectToRoute("app_admin_category", ["id"=>$idCategory]);


    }

    public function genereMatch($poules, $phase, $entityManager)
    {
         //on génère les matchs
                foreach ($poules as $poule)
                {
                    $tours = [];
                    $meets = [];

                    for ($i = 1; $i <= count($poule->getTeams()) - 1; $i++) {
                        $tours[$i] = [];
                    }


                    foreach ($poule->getTeams() as $teamA)
                    {
                        foreach ($poule->getTeams() as $teamB)
                        {
                            $find = false;

                            foreach ($meets as $meet2) {
                                if (($meet2->getTeamA() === $teamA && $meet2->getTeamB() === $teamB) OR ($meet2->getTeamA() === $teamB && $meet2->getTeamB() === $teamA)) {
                                    $find = true;
                                }
                            }

                            if ($teamA !== $teamB && !$find) {
                                $match = new Meet();
                                $match->setTeamA($teamA);
                                $match->setTeamB($teamB);
                                $match->setPhase($phase);
                                $match->setPoule($poule);

                                $tour = $this->findCorrectTour($tours, $teamA, $teamB);
                                $match->setTour($tour);

                                $entityManager->persist($match);
                                $entityManager->flush();
                                $meets[] = $match;
                                $tours[$tour][] = ["a"=>$teamA,"b"=>$teamB];
                            }

                        }
                    }
                }
    }

}
