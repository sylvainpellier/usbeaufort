<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Param;
use App\Repository\CategoryRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function json_decode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class OverrideController extends OverrideApiController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $params = $this->em->getRepository(Param::class)->findAll();
        $parameters["params"] = [];

        foreach($params as $p)
        {
            $parameters["params"][$p->getName()] = $p->getValue();
        }

        $parameters["categories"] = $this->em->getRepository(Category::class)->findAll();

        return parent::render($view, $parameters, $response);
    }

}
