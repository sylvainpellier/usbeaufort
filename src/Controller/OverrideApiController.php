<?php

namespace App\Controller;

use App\Repository\TeamRepository;
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

    public function send($dataToSend,$param = null): Response
    {
        $data = [];
        $data["ok"] = true;
        $data["param"] = $param;
        $data["data"] = json_decode($dataToSend);

        return $this->json(($data));


    }
}
