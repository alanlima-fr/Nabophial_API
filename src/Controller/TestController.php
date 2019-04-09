<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    protected $entity = 'App\Entity\Test';
    protected $namespaceType = 'App\Form\TestType';
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Get("/test")
     */
    public function getTest()
    {
        $tests =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$tests)
            return resourceNotFound();

        return $tests;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/test/{id}")
     */
    public function getOneTest($id)
    {
        $test = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);

        if (!$test)
            return resourceNotFound();

        return $test;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View()
     * @Rest\Post("/test")
     */
    public function postTest(Request $request)
    {
        $test = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $test);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($test);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $resource;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    protected function resourceNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Resource not found');
    }
}
