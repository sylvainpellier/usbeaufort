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
     * @Route("/fields", name="fields_index")
     */
    public function public_index(): Response
    {
        return $this->render("terrains.html.twig");

    }

    /**
     * @Route("/api/fields", name="fields_api")
     */
    public function index(FieldRepository $fieldRepository, SerializerInterface $serializer): Response
    {
       return $this->send($fieldRepository->findAll(),["field"],$serializer);
    }
}
