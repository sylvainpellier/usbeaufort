<?php

namespace App\Controller;

use App\Repository\FieldRepository;
use function json_encode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use function var_dump;



class FieldController extends OverrideApiController
{

    /**
     * @Route("/terrain/{id}", name="terrain_show")
     */
    public function terrain_show(string $id, FieldRepository $fieldRepository): Response
    {
        return $this->render("terrains/show.html.twig",["terrain"=>$fieldRepository->find($id)]);

    }

    /**
     * @Route("/terrains", name="terrains_index")
     */
    public function terrains_index(): Response
    {
        return $this->render("terrains/index.html.twig");

    }

    /**
     * @Route("/api/fields", name="fields_api")
     */
    public function index(FieldRepository $fieldRepository, SerializerInterface $serializer): Response
    {
       return $this->send($serializer->serialize($fieldRepository->findAll(),'json',['groups' => ['field']]));
    }
}
