<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RelationController extends AbstractController
{
    /**
     * @Route("/relation", name="relation")
     */
    public function index()
    {
        return $this->render('relation/index.html.twig', [
            'controller_name' => 'RelationController',
        ]);
    }
}
