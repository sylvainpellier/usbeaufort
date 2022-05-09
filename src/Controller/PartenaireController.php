<?php

namespace App\Controller;

use App\Form\PartenaireType;
use App\Form\PartenaireSimpleType;

use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use function uniqid;

class PartenaireController extends OverrideController
{
    /**
     * @Route("/admin/partenaires", name="app_admin_partenaires")
     */
    public function admin(Request $request, EntityManagerInterface $entityManager, PartenaireRepository $partenaireRepository): Response
    {
        $form = $this->createForm(PartenaireSimpleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partenaire = $form->getData();

            $entityManager->persist($partenaire);
            $entityManager->flush();
            $this->addFlash("success","Partenaire créé avec succès");
        }

        return $this->render('admin/partenaire.html.twig', [
            'partenaires'=>$partenaireRepository->findAll(),
            "formEmpty" => $this->createForm(PartenaireSimpleType::class)->createView()

        ]);
    }


    /**
     * @Route("/admin/partenaire/{id}", name="app_admin_partenaire_edit")
     */
    public function app_admin_partenaire_edit(string $id, Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, PartenaireRepository $partenaireRepository): Response
    {
        $partenaire = $partenaireRepository->find($id);
        $form = $this->createForm(PartenaireType::class, $partenaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $partenaire = $form->getData();

            $file = $form->get('logoFile')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                       "img",
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $partenaire->setLogo($newFilename);
            }


            $entityManager->persist($partenaire);
            $entityManager->flush();
            $this->addFlash("success","Partenaire modifié avec succès");
            return $this->redirectToRoute("app_admin_partenaires");
        }

        return $this->render('admin/partenaire.html.twig', [
            'form' => $form->createView(),
            'partenaire' => $partenaireRepository->find($id),
            'partenaires'=>$partenaireRepository->findAll()
        ]);
    }



    /**
     * @Route("/admin/partenaire/{id}/delete", name="app_admin_partenaire_delete")
     */
    public function app_admin_partenaire_delete(string $id, EntityManagerInterface $entityManager, PartenaireRepository $partenaireRepository): Response
    {

        $partenaire = $partenaireRepository->find($id);
        $entityManager->remove($partenaire);
        $entityManager->flush();
        $this->addFlash("success","Partenaire supprimé avec succès");

        return $this->redirectToRoute("app_admin_partenaires");
    }


    /**
     * @Route("/partenaire", name="app_partenaire")
     */
    public function index(PartenaireRepository $partenaireRepository): Response
    {
        return $this->render('partenaire/index.html.twig', [
            'partenaires'=>$partenaireRepository->findAll()
        ]);
    }
}
