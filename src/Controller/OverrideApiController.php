<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class OverrideApiController extends AbstractController
{

    public function send($data, array $group, SerializerInterface $serializer): Response
    {


        return $this->json(json_decode($serializer->serialize($data, 'json', ['groups' => $group])));


    }
}
