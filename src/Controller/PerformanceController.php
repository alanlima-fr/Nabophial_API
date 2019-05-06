<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PerformanceController extends AbstractController
{
    protected $entity = 'App\Entity\Performance';

    /**
     * Recupere tous les donnees de la table Performance
     * 
     * @Rest\View()
     * @Rest\Get("/performance")
     */
    public function getPerformance()
    {
        $performances = $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$performances)
            $this->resourceNotFound();
        return $performances;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/performance/{id}")
     */
    public function getOnePerformance($id)
    {
        $performance = $this->findOne($id);

        if (!$performance)
            $this->resourceNotFound();
        return $performance;
    }
    
    
    

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/performance/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    

    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/performance/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $performance = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($performance)
        {
            $em->remove($performance);
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
