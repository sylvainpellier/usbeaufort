<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use function json_encode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use function var_dump;

/**
 * @Route("/api/teams", name="api_teams_")
 */

class TeamController extends OverrideApiController
{
    /**
     * @Route("/", name="all")
     */
    public function index(TeamRepository $teamRepository, SerializerInterface $serializer): Response
    {
       return $this->send($teamRepository->findAll(),["team"],$serializer);
    }
}
