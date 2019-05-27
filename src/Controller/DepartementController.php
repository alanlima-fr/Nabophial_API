<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DepartementController extends AbstractController
{
    protected $entity = 'App\Entity\Departement';
    
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Get("/departement")
     */
    public function getDepartement()
    {
        $departements =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$departements)
            $this->resourceNotFound();

        return $departements;
    }
    
    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Get("/departement/{id}")
     */
    public function getOneDepartement($id)
    {
        $departement = $this->findOne($id);

        if (!$departement)
            $this->resourceNotFound();

        return $departement;
    }

    /**
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Resource not found or empty');
    }

    /**
     * Return a resource by his id.
     */
    protected function findOne($id)
    {
        return $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
    }

}
