<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\Team;
use App\Repository\CategoryRepository;
use App\Repository\MeetRepository;
use App\Repository\PhaseRepository;
use App\Repository\TeamRepository;
use function array_key_exists;
use function array_push;
use function array_splice;
use function d;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/admin/addTeam/{idCategory}", name="app_add_team")
     */
    public function app_add_team(Request $request, EntityManagerInterface $entityManager, string $idCategory, CategoryRepository $categoryRepository): Response
    {

        if($request->get("nameTeam") && $request->get("idCategory"))
        {
            $category = $categoryRepository->find($request->get("idCategory"));

                $team = new Team();
                $team->setCategory($category);
                $team->setName($request->get("nameTeam"));
                $entityManager->persist($team);
                $entityManager->flush();

                $this->addFlash("success","Équipe ajoutée avec succès");
        }

        return $this->render('admin/addTeam.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'category' => $categoryRepository->find($idCategory)
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
    public function categoryphase(string $idCategory, string $idPhase, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $c = new ApiController($entityManager);

        return $this->render('admin/category_phase.html.twig', [
            'category' => $categoryRepository->find($idCategory),
            'phase' => $phaseRepository->find($idPhase),
            'categories' => $categoryRepository->findAll(),
            'classement' =>$c->data($idCategory,$idPhase, $conn = $entityManager->getConnection(),false)
        ]);
    }


    /**
     * @Route("/admin/category/{idCategory}/phase/{idPhase}/generate", name="app_admin_category_phase_generate")
     */
    public function generatePhase(string $idCategory, TeamRepository $teamRepository, MeetRepository $meetRepository, string $idPhase, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $phase = $phaseRepository->find($idPhase);

        $matchs = $meetRepository->findBy(["Phase"=>$phase]);


        //On supprime tous les matchs de la phase
    foreach ($matchs as $match)
        {
            $entityManager->remove($match);
            $entityManager->flush();
        }

        $teams = $teamRepository->findBy(["Category"=>$idCategory]);
        $teamByPoule = $phase->getType()->getTeamByPoule();

        //mode normal
        if($phase->getType()->getFormat() === "all_teams")
        {
            //shuffle($teams);

            $poules = [];
            $alphas = range('A', 'Z');
            $pouleC = 0;
            $count=1;
            foreach ($teams as $team)
            {

                if($count>$teamByPoule) {
                    $pouleC++;
                    $count = 1;
                }
                if(!array_key_exists($alphas[$pouleC],$poules)) {
                    $poules[$alphas[$pouleC]] = [];
                }
                $poules[$alphas[$pouleC]][] = $team;
                $count++;

            }



        } else if($phase->getType()->getFormat() === "principal-consolante") //Mode principal-consolante
        {
            $equipes_principales = [];
            $equipes_consolantes = [];

            $c = new ApiController($entityManager);
            $resultats = $c->data($idCategory,$phase->getPhasePrecente()->getId(),$entityManager->getConnection());

            $poulesTemporaire = [];
            $poulesTemporaire["equipes_principales"] = [];
            $poulesTemporaire["equipes_consolantes"] = [];
            foreach($resultats as $poule)
            {
                foreach($poule as $key=>$t)
                {
                    if($key <= 1)
                    {
                        $poulesTemporaire["equipes_principales"][] = $t;
                    } else
                    {
                        $poulesTemporaire["equipes_consolantes"][] = $t;
                    }
                }
            }

            $poules = [];
            $alphas = range('A', 'Z');
            $pouleC = 0;
            $c=0;
            foreach($poulesTemporaire as $nameT =>  $pouleT)
            {
                foreach ($pouleT as $team)
                {

                    if($c>=$teamByPoule) {
                        $pouleC++;
                        $c = 0;
                    }
                    $name = $alphas[$pouleC] . " " . ( $nameT === "equipes_principales" ? " Principale" : "Consolante" );
                    if(!array_key_exists($name,$poules)) {
                        $poules[$name] = [];
                    }
                    $poules[$name][] = $teamRepository->find($team["id"]);
                    $c++;

                }
            }




        }




        foreach($poules as $keyPoule => $poule) {

            $teamsPoule = $poule;

            $meets = [];
            foreach ($teamsPoule as $teamA) {

                foreach ($teamsPoule as $teamB) {

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
                        $match->setPoule($keyPoule);

                        $meets[] = $match;

                        $entityManager->persist($match);
                    }
                }
            }


            $entityManager->flush();
            $tours = [];
            for ($i = 1; $i <= count($teamsPoule) - 1; $i++) {
                $tours[$i] = [];
            }

            foreach ($meets as $key_match => &$match) {

                $tourToAdd = $this->findCorrectTour($tours, $match->getTeamA(), $match->getTeamB());


                $tours[$tourToAdd][] = $match;
                $match->setTour((int)$tourToAdd);
                $entityManager->persist($match);
                $entityManager->flush();


            }


        }
        return $this->redirectToRoute("app_admin_category_phase", ["idCategory"=>$idCategory,"idPhase"=>$idPhase]);
    }

    public function findCorrectTour($tours, $teamA, $teamB)
    {

        foreach($tours as $key => &$tour)
        {
            if(count($tour) === 0)
            {
                return $key;
            } else {
                foreach ($tour as $matchDansTour) {

                    if ($matchDansTour->getTeamA() !== $teamA && $matchDansTour->getTeamA() !== $teamB && $matchDansTour->getTeamB() !== $teamB && $matchDansTour->getTeamB() !== $teamA) {
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
    public function categoryphasgroupe(string $idCategory, string $idPhase, string $groupe, EntityManagerInterface $entityManager, PhaseRepository $phaseRepository, CategoryRepository $categoryRepository): Response
    {

        $c = new ApiController($entityManager);

        return $this->render('admin/category_phase_groupe_matchs.html.twig', [
            'category' => $categoryRepository->find($idCategory),
            'phase' => $phaseRepository->find($idPhase),
            'groupe' => $groupe,
            'categories' => $categoryRepository->findAll(),
            'classement' =>$c->data($idCategory,$idPhase, $conn = $entityManager->getConnection(), $groupe)
        ]);
    }
}
