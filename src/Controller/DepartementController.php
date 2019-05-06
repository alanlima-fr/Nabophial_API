<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DepartementController extends AbstractController
{
    protected $entity = 'App\Entity\City';
    
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Get("/departement")
     */
    public function getCity()
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
     * @Rest\View()
     * @Rest\Get("/departement/{id}")
     */
    public function getOneCity($id)
    {
        $departement = $this->findOne($id);

        if (!$departement)
            $this->resourceNotFound();

        return $departement;
    }

    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/departement/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $departement = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($departement)
        {
            $em->remove($departement);
            $em->flush();
        }
        else
            $this->resourceNotFound();
    }

    /**
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Resource not found');
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
