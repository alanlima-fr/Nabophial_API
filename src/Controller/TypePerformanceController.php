<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TypePerformanceController extends AbstractController
{
    /**
     * @Route("/typePerformance", name="type_performance")
     */
    public function index()
    {
        return $this->render('type_performance/index.html.twig', [
            'controller_name' => 'typePerformanceController',
        ]);
    }
}
