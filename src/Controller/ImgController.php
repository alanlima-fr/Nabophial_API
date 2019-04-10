<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImgController extends AbstractController
{
    /**
     * @Rest\View()
     * @Rest\Get("/img")
     *   \|/ J'ai pas fini ici \|/
     */
    public function getimg($photo) 
    {
        
        $photo = '';
        
    if (!$photo) {
        throw $this->createNotFoundException('The picture does not exist');
    }

        return $this->render('img/index.html.twig', [
            'controller_name' => 'ImgController',
        ]);
    }
}
