<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PerformanceController extends AbstractController
{
    /**
     * @Route("/performance", name="performance")
     */
    public function index()
    {
        return $this->render('performance/index.html.twig', [
            'controller_name' => 'PerformanceController',
        ]);
    }
}
