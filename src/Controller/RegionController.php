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
     * @Rest\View(serializerGroups={"all"})
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
     * @Rest\View(serializerGroups={"all"})
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
