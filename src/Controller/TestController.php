<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Rest\View()
     * @Rest\Get("/test")
     */
    public function getTest()
    {
        return array(
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        );
    }
}
