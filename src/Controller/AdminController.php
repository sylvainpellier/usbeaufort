<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\Phase;
use App\Entity\Position;
use App\Entity\Poule;
use App\Entity\PouleTeam;
use App\Entity\RangTroisieme;
use App\Entity\Team;
use App\Form\PhaseType;
use App\Repository\CategoryRepository;
use App\Repository\FieldRepository;
use App\Repository\MeetRepository;
use App\Repository\PhaseRepository;
use App\Repository\PositionRepository;
use App\Repository\PouleRepository;
use App\Repository\PouleTeamRepository;
use App\Repository\RangTroisiemeRepository;
use App\Repository\TeamRepository;
use function array_key_exists;
use function array_push;
use function array_search;
use function array_splice;
use function d;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function shuffle;
use function str_contains;
use function str_replace;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use function usort;
use function var_dump;

class AdminController extends OverrideController
{
    /**
     * @Route("/admin/egalite", name="app_admin_egalite")
     */
    public function app_admin_egalite(Request $request, PouleTeamRepository $pouleTeamRepository, EntityManagerInterface $entityManager, TeamRepository $teamRepository, PouleRepository $pouleRepository)
    {
        $team = $teamRepository->find($request->get("team"));
        $poule = $pouleRepository->find($request->get("poule"));

        $tm = $pouleTeamRepository->findOneBy(["Team"=>$team, "Poule"=>$poule]) ?? new PouleTeam();

        $tm->setTeam($team);
        $tm->setPoule($poule);
        $tm->setRang($request->get("rang"));

        $entityManager->persist($tm);
        $entityManager->flush();

        return $this->redirectToRoute("app_admin_category_phase",["idCategory"=>$request->get("idCategory"),"idPhase"=>$request->get("idPhase") ]);
    }

    /**
     * @Route("/admin/egalite/troisieme", name="app_admin_egalite_troisieme")
     */
    public function app_admin_egalite_troisieme(Request $request, PhaseRepository $phaseRepository, RangTroisiemeRepository $rangTroisiemeRepository, PouleTeamRepository $pouleTeamRepository, EntityManagerInterface $entityManager, TeamRepository $teamRepository, PouleRepository $pouleRepository)
    {
        $team = $teamRepository->find($request->get("team"));
        $phase = $phaseRepository->find($request->get("idPhase"));

        $rt = $rangTroisiemeRepository->findOneBy(["Team"=>$team, "Phase"=>$phase]) ?? new RangTroisieme();

        $rt->setTeam($team);
        $rt->setPhase($phase);
        $rt->setRang($request->get("rang"));

        $entityManager->persist($rt);
        $entityManager->flush();

        return $this->redirectToRoute("app_admin_category_phase",["idCategory"=>$request->get("idCategory"),"idPhase"=>$request->get("idPhase") ]);
    }


    /**
     * @Route("/admin/next/{idCategory}/{idPhase}", name="app_admin_next")
     */
    public function app_admin_next(string $idCategory, string $idPhase)
    {
        return $this->render('admin/next.html.twig', [
            "idCategory" => $idCategory,
            'idPhase' => $idPhase
        ]);
    }

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
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/admin/saisie/{id}", name="app_admin_saisie")
     */
    public function app_admin_saisie( string $id, Request $request, FieldRepository $fieldRepository, MeetRepository $meetRepository, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {
        $session = $request->getSession();
        $session->start();

        if(  $request->get('back') )
        {
            $session->set("back",$request->get('back'));
        }

        return $this->render('admin/meet/saisie.html.twig', [
            'meet' => $meetRepository->find($id),
            'fields' => $fieldRepository->findAll(),
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
    public function category(string $id, FieldRepository $fieldRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category.html.twig', [
            'category' => $categoryRepository->find($id),
            'fields' => $fieldRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}", name="app_admin_category_phase")
     */
    public function categoryphase(string $idCategory, RangTroisiemeRepository $rangTroisiemeRepository, FieldRepository $fieldRepository, string $idPhase, PositionRepository $positionRepository, MeetRepository $meetRepository, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $c = new ApiController($entityManager);
        $phase = $phaseRepository->find($idPhase);
        $positions = new ArrayCollection();
        foreach($categoryRepository->find($idCategory)->getPhases() as $phaseS)
        { foreach($phaseS->getPositions() as $p)
            {
                $positions->add($p);
            }

        }

        $data = $c->data($idCategory,$phase, $conn = $entityManager->getConnection(),false);

        $troisiemes = [];
        if($phase->getParam() == 24) {

            foreach ($data as $kP => $poule) {
                foreach ($poule as $kT => $team) {
                    if ($team["rang"] == 3) {

                        $team["rangTroisieme"] = $rangTroisiemeRepository->findOneBy(["Phase"=>$phase,"Team"=>$team["id"]]);
                        $troisiemes[] = $team;
                    }
                }
            }
        }

        usort($troisiemes, array($this,'triClassementSpecialTroisime'));
        //usort($troisiemes, array($this,'triClassementBonus'));

        $i = 1;
        foreach($troisiemes as $k => $team)
        {
            $troisiemes[$k]["rang"] = $i;
            $i++;
        }

        return $this->render('admin/category_phase.html.twig', [
            'category' => $categoryRepository->find($idCategory),
            'positions' => $positions,
            'troisiemes'=>$troisiemes,
            'phase' => $phase,
            'fields' => $fieldRepository->findAll(),
            'groupes' => $meetRepository->findGroupes($idCategory,$idPhase),
            'categories' => $categoryRepository->findAll(),
            'classement' =>$data
        ]);
    }


    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/delete", name="app_admin_category_phase_delete")
     */
    public function category_phase_delete(string $idCategory, RangTroisiemeRepository $rangTroisiemeRepository, TeamRepository $teamRepository, MeetRepository $meetRepository, string $idPhase, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {
        $phase = $phaseRepository->find($idPhase);
        $matchs = $meetRepository->findBy(["Phase"=>$phase]);
        $rangs = $rangTroisiemeRepository->findBy(["Phase"=>$phase]);

        //On supprime tous les matchs de la phase
        foreach ($matchs as $match)
        {
            $entityManager->remove($match);
            $entityManager->flush();
        }

        //On supprime tous les matchs de la phase
        foreach ($rangs as $rang)
        {
            $entityManager->remove($rang);
            $entityManager->flush();
        }



        return $this->redirectToRoute("app_admin_category_phase", ["idCategory"=>$idCategory,"idPhase"=>$idPhase]);


    }

    /**
     * @Route("/admin/all_matchs/delete", name="app_admin_delete")
     */
    public function app_admin_delete( TeamRepository $teamRepository, MeetRepository $meetRepository,  EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {
        $matchs = $meetRepository->findAll();


        //On supprime tous les matchs de la phase
        foreach ($matchs as $match)
        {
            $entityManager->remove($match);
            $entityManager->flush();
        }



        return $this->redirectToRoute("app_admin");


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
        $sql = " UPDATE usb_meets SET penalty_a = null, penalty_B = null, score_a = FLOOR( 1 + RAND( ) *3 ) , score_b = FLOOR( 1 + RAND( ) *3 ) WHERE phase_id = :phase ";
        $stmt = $conn->prepare($sql);
        $parameters = ["phase"=>$idPhase];
        $stmt->executeQuery($parameters);

//        $conn = $entityManager->getConnection();
//        $sql = " UPDATE usb_meets SET penalty_a = FLOOR( 1 + RAND( ) *5 ) , penalty_b = FLOOR( 1 + RAND( ) *5 ) WHERE score_a = score_b AND phase_id = :phase";
//        $stmt = $conn->prepare($sql);
//        $parameters = ["phase"=>$idPhase];
//        $stmt->executeQuery($parameters);

        $this->addFlash("success","Score simulé avec succès");

        return $this->redirectToRoute("app_admin_category_phase",["idPhase"=>$idPhase,"idCategory"=>$idCategory]);


        ;

    }

    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/generate", name="app_admin_category_phase_generate")
     */
    public function generatePhase(string $idCategory, RangTroisiemeRepository $rangTroisiemeRepository, PouleRepository $pouleRepository, PositionRepository $positionRepository, TeamRepository $teamRepository, MeetRepository $meetRepository, string $idPhase, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $phase = $phaseRepository->find($idPhase);
        $phase = $phase->getPhaseSuivante() ?  $phase->getPhaseSuivante() : $phase;
        $phase->getCategory()->setPhaseEnCours($phase);
        $entityManager->persist($phase);

        if($phase->getType()->getFormat() === "principal-consolante" || $phase->getType()->getFormat()=== "demifinalesfinales")
        {

        $c = new ApiController($entityManager);
        $data = $c->data($idCategory,$phase->getPhasePrecedente(), $conn = $entityManager->getConnection());

            $wait = [];




        foreach($data as $keyGroupe => $teamsGroupe)
        {
            foreach ($teamsGroupe as $team)
            {
                $t = $teamRepository->find($team["id"]);

                $checkTroisieme = $rangTroisiemeRepository->findOneBy(["Phase"=>$idPhase,"Team"=>$team["id"]]);

                if($checkTroisieme)
                {
                    $position = $positionRepository->findOneBy(["Rang"=> "3", "int_param"=> $checkTroisieme->getRang()]);

                } else
                {
                    $position = $positionRepository->findOneBy(["PouleFrom"=> $team["poule"], "Rang"=> $team["rang"]]);
                }
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
        //$teams = $poule->getTeams();

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

                $finded = false;
                foreach ($tour as $matchDansTour) {


                    if ($matchDansTour["a"]->getId() != $teamA->getId() && $matchDansTour["b"]->getId() != $teamB->getId() && $matchDansTour["a"]->getId() != $teamB->getId() && $matchDansTour["b"]->getId() != $teamA->getId()) {


                    } else
                    {
                        $finded = true;
                    }
                }

                if(!$finded)
                {
                    return $key;
                }
            }
        }

    }

    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/groupe/{groupe}", name="app_admin_category_phase_groupe")
     */
    public function categoryphasgroupe(string $idCategory, FieldRepository $fieldRepository, PouleRepository $pouleRepository, string $idPhase, string $groupe, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $c = new ApiController($entityManager);
        $phase = $phaseRepository->find($idPhase);
        return $this->render('admin/category_phase_groupe_matchs.html.twig', [
            'category' => $categoryRepository->find($idCategory),
            'phase' => $phase,
            'groupe' => $groupe,
            'fields' => $fieldRepository->findAll(),
            'poule' => $pouleRepository->find($groupe),
            'categories' => $categoryRepository->findAll(),
            'classement' =>$c->data($idCategory,$phase, $conn = $entityManager->getConnection(), $groupe)
        ]);
    }

    /**
     * @Route("/category/{idCategory}/phase/{idPhase}/groupe/{groupe}", name="app_category_phase_groupe")
     */
    public function app_category_phase_groupe(string $idCategory, FieldRepository $fieldRepository, PouleRepository $pouleRepository, string $idPhase, string $groupe, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $c = new ApiController($entityManager);
        $phase = $phaseRepository->find($idPhase);
        return $this->render('category_phase_groupe_matchs.html.twig', [
            'category' => $categoryRepository->find($idCategory),
            'phase' => $phase,
            'groupe' => $groupe,
            'fields' => $fieldRepository->findAll(),
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

        $category = $categoryRepository->find($idCategory);
        $phases = $category->getPhases();
        $category->setPhaseEnCours(null);
        $entityManager->persist($category);

        foreach ($phases as $phase) {
            //On supprime les matchs existant
            $matchs = $meetRepository->findBy(["Phase" => $phase]);

            //On supprime tous les matchs de la phase
            foreach ($matchs as $match) {
                $entityManager->remove($match);
            }


            foreach ($phase->getPoules() as $poule) {
                $entityManager->remove($poule);
            }


        }

        $entityManager->flush();

        return $this->redirectToRoute("app_admin_category_generate_matchs", ["idCategory" => $idCategory]);
    }

    /**
     * @Route("/admin/category/{idCategory}/generate_matchs", name="app_admin_category_generate_matchs")
     */
    public function app_admin_category_generate_matchs(string $idCategory, Request $request, PositionRepository $positionRepository, PouleRepository $pouleRepository, TeamRepository $teamRepository, MeetRepository $meetRepository, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {


        $category = $categoryRepository->find($idCategory);

        $teams = $teamRepository->findBy(["Category"=>$category], ["GroupeInitial"=>"ASC","Rang"=>"ASC"]);
        $category->setPhaseEnCours(null);
        $entityManager->persist($category);
        $conn = $entityManager->getConnection();


        $idPhase = $request->get("idPhase");
        if($idPhase)
        {
            $phase = $phaseRepository->find($idPhase);
        }
        if(!$idPhase || !$phase)
        {
            $phase = $phaseRepository->findOneBy(["category"=>$category,"PhasePrecedente"=>null]);
        }


            $poules = [];
            $nbTeamByPoule = $phase->getType()->getTeamByPoule();
            $count3=1;

            if($phase->getType()->getFormat() === "normal")
            {
                for($i = 0; $i <= (count($teams) / $nbTeamByPoule) -1 ; $i++)
                {
                    $poule = new Poule();
                    $poule->setName("Groupe ".($i+1));
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


                //$entityManager->flush();

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
                $consolanteCount = 1;
                for($i = 0; $i <= (count($teams) / $nbTeamByPoule) -1 ; $i++)
                {

                    $poule = new Poule();
                    $poule->setPhase($phase);


                    if( ($phase->getParam() == 32 && $i< (count($teams) / $nbTeamByPoule) / 2) || ($phase->getParam() == 24 && $i < 4))
                    {
                        $poule->setPrincipal(true);
                        $poule->setName("Principale ".($i+1));

                    } else
                    {
                        $poule->setPrincipal(false);
                        $poule->setName("Consolante ".$consolanteCount);
                        $consolanteCount++;
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
                    $poules[] = $poule;
                }

                $entityManager->flush();


                while(count($positionRepository->findBy([ "PhaseFrom"=>$phase->getPhasePrecedente(), "PouleTo" => null])) > 0) {

                    shuffle($poules);
                    foreach ($poules as $poule) {

                        //echo $poule."<br />";

                        for ($rang = 1; $rang <= $nbTeamByPoule; $rang++) {

                            //if ($phase->getParam() == 32 || ($phase->getParam() == 24 && $rang != 3)) {
                            $parameters = [];
                            $sql = "SELECT * FROM usb_positions p WHERE p.phase_from_id = :phase  ";
                            $parameters["phase"] = $phase->getPhasePrecedente()->getId();
                            if ($phase->getParam() == 32) {
                                $sql .= " AND p.poule_from_id NOT IN ( SELECT p2.poule_from_id FROM usb_positions p2 WHERE p2.poule_to_id = :poule ) ";
                                $sql .= " AND p.rang != :rang ";
                                $parameters["rang"] = $rang;
                                $parameters["poule"] = $poule->getId();
                                $sql .= ($poule->getPrincipal()) ? " AND p.rang <= 2" : " AND p.rang > 2";

                            } else {
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
                                        if (($positionRepository->check($poule->getId(), $rang)) <= 1) {
                                            if (($positionRepository->check($poule->getId())) < 4) {
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
                    if($phase->getParam() == 32 || $phase->getParam() == 24)
                    {
                        $name = "Matchs de classement ".((($i)*4)+1)."e - ".((($i+1)*4))."e";
                        $poule->setName($name);


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
                $i=0;
                foreach ($poules as $poule) {
                    $i++;
                    $positions = $positionRepository->findBy(["PouleTo" => $poule]);


                    if(count($positions) === 4) {
                        shuffle($positions);

                        $match = new Meet();
                        $match->setPoule($poule);
                        $match->setPositionA($positions[0]);
                        $match->setPositionB($positions[1]);

                        switch($i)
                        {
                            case 1 : $name = "Demi finale A "; break;
                            default : $name = "Demi ".((($i-1)*4)+1)."e - ".(($i*4))."e A"; break;

                        }

                        $match->setName($name);
                        $match->setTour(1);
                        $match->setFormat("demi");
                        $match->setPrincipal($poule->getPrincipal());
                        $match->setPhase($phase);
                        $entityManager->persist($match);
                        $match->setParam($i);
                        $match = new Meet();
                        $match->setPoule($poule);
                        $match->setPositionA($positions[2]);
                        $match->setPositionB($positions[3]);

                        switch($i)
                        {
                            case 1 : $name = "Demi finale B "; break;
                            default : $name = "Demi ".((($i-1)*4)+1)."e - ".(($i*4))."e B"; break;

                        }

                        $match->setName($name);
                        $match->setTour(1);
                        $match->setParam($i);
                        $match->setFormat("demi");
                        $match->setPrincipal($poule->getPrincipal());
                        $match->setPhase($phase);
                        $entityManager->persist($match);

                        $match = new Meet();
                        $match->setPoule($poule);

                        switch($i)
                        {
                            case 1 : $name = "Match 3e - 4e "; break;
                            default : $name = "Match ".((($i-1)*4)+3)."e - ".((($i-1)*4)+4)."e"; break;

                        }

                        $match->setParam($i);
                        $match->setName($name);
                        $match->setFormat("final_perdant");
                        $match->setTour(2);
                        $match->setPrincipal($poule->getPrincipal());
                        $match->setPhase($phase);
                        $entityManager->persist($match);

                        $match = new Meet();
                        $match->setPoule($poule);
                        switch($i)
                        {
                            case 1 : $name = "Finale "; break;
                            default : $name = "Match ".((($i-1)*4)+1)."e - ".((($i-1)*4)+2)."e"; break;

                        }

                        $match->setName($name);
                        $match->setFormat("final_gagnant");
                        $match->setTour(3);
                        $match->setParam($i);
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








            $entityManager->flush();

            if($phase->getPhaseSuivante())
            {
                return $this->redirectToRoute("app_admin_category_generate_matchs", ["idCategory"=>$idCategory,"idPhase"=>$phase->getPhaseSuivante()->getId()]);
            }

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
