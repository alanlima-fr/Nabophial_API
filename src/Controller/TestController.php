<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Test;

class TestController extends AbstractController
{
    /**
     * @Rest\View()
     * @Rest\Get("/test")
     */
    public function getTest()
    {
        return $tests =  $this->getDoctrine()
        ->getRepository(Test::class)
        ->findAll();
    }
}
