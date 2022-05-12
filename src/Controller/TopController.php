<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopController extends OverrideController
{
    /**
     * @Route("/top", name="app_top")
     */
    public function index(): Response
    {
        return $this->render('top/index.html.twig', [
            'controller_name' => 'TopController',
        ]);
    }
}
