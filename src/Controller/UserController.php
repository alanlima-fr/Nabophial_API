<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Rest\View()
     * @Rest\Get("/user")
     */
    public function getUser() 
    {
        return array(
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        );
    }
}
