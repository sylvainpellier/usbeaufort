<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\TeamRepository;
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

class OverrideApiController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function triClassement($a,$b)
    {

            //PRENDRE EN COMPTE LES ÉGALITÉS
            //DIVERSES VOIR LE RELGEMENT
            //TODO : classement
            if($a['pts'] === $b['pts'])
            {
                //TODO : égalité confrontation direct ???

                //égalité confrontation directe
                if ((((int)$a["but_pour"]-(int)$a["but_contre"]))  === (((int)$b["but_pour"]-(int)$b["but_contre"])))
                {
                    //TODO : pénalties ?
                } else
                {
                    return (((int)$a["but_pour"]-(int)$a["but_contre"])) < (((int)$b["but_pour"]-(int)$b["but_contre"]));
                }
            } else
            {
                return $a['pts']<$b['pts'];
            }



    }
    public function render(string $view, array $parameters = [], Response $response = null): Response
    {

        $parameters["categories"] = $this->em->getRepository(Category::class)->findAll();

        return parent::render($view, $parameters, $response);
    }

    public function send($dataToSend,$param = null): Response
    {
        $data = [];
        $data["ok"] = true;
        $data["param"] = $param;
        $data["data"] = json_decode($dataToSend);

        return $this->json(($data));


    }
}
