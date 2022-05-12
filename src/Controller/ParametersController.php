<?php

namespace App\Controller;

use App\Form\ParameterType;
use App\Repository\ParamRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParametersController extends OverrideController
{
    /**
     * @Route("/admin/parameters", name="app_admin_parameters")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, ParamRepository $paramRepository): Response
    {
        $form = $this->createForm(ParameterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $param = $form->getData();

            $entityManager->persist($param);
            $entityManager->flush();
            $this->addFlash("success","Paramètre créé avec succès");
        }

        return $this->render('admin/parameters/index.html.twig', [
            'paramaters' => $paramRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/parameter/update", name="app_admin_parameters_update")
     */
    public function app_admin_parameters_update(Request $request, EntityManagerInterface $entityManager, ParamRepository $paramRepository): Response
    {
        $id = $request->get("id");
        $value = $request->get("value");

        $p = $paramRepository->find($id);
        if($p)
        {
            $p->setValue($value);
            $entityManager->persist($p);
            $entityManager->flush();
        }

        return $this->redirectToRoute("app_admin_parameters");

    }

    /**
     * @Route("/admin/parameter/{id}/delete", name="app_admin_parameters_delete")
     */
    public function app_admin_parameters_delete(Request $request, string $id, EntityManagerInterface $entityManager, ParamRepository $paramRepository): Response
    {

        $p = $paramRepository->find($id);
        if($p)
        {
            $entityManager->remove($p);
            $entityManager->flush();
        }

        return $this->redirectToRoute("app_admin_parameters");
    }
}
