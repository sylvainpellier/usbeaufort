<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use function json_encode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use function var_dump;




class CategoryController extends OverrideApiController
{


    /**
     * @Route("/categorie/{id}/phase/{phase}", name="category_display_phase")
     */
    public function display_phase(string $id, string $phase, CategoryRepository $categoryRepository, SerializerInterface $serializer): Response
    {
        return $this->render("category_phase.html.twig", ["category"=>$categoryRepository->find($id), "phase" => $phase]);
    }

    /**
     * @Route("/categorie/{id}", name="category_display")
     */
    public function display(string $id, CategoryRepository $categoryRepository, SerializerInterface $serializer): Response
    {
        $categorie = $categoryRepository->find($id);
        $phaseEnCours = $categorie->getPhaseEnCours();

        if($phaseEnCours)
        {
            return $this->display_phase($id, $phaseEnCours->getId(), $categoryRepository, $serializer);
        } else
        {
            return $this->render("category.html.twig", ["category"=>$categoryRepository->find($id)]);
        }

    }



    /**
     * @Route("/api/categories", name="api_categories_all")
     */
    public function index(CategoryRepository $categoryRepository, SerializerInterface $serializer): Response
    {
       return $this->send($serializer->serialize($categoryRepository->findAll(),'json',['groups' => ['category']]));
    }
}
