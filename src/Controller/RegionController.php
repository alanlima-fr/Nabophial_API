<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RegionController extends AbstractController
{
    protected $entity = 'App\Entity\Region';
    
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Get("/region")
     */
    public function getRegion()
    {
        $regions =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$regions)
            $this->resourceNotFound();

        return $regions;
    }
    
    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/region/{id}")
     */
    public function getOneRegion($id)
    {
        $region = $this->findOne($id);

        if (!$region)
            $this->resourceNotFound();

        return $region;
    }

    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/region/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $region = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($region)
        {
            $em->remove($region);
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
