<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class StatusController extends AbstractController
{
    protected $entity = 'App\Entity\Status';
    protected $namespaceType = 'App\Form\StatusType';
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View(serializerGroups={"all", "status"})
     * @Rest\Get("/status")
     */
    public function getStatus()
    {
        $status =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$status)
            $this->resourceNotFound();

        return $status;
    }
    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View(serializerGroups={"all", "status"})
     * @Rest\Get("/status/{id}")
     */
    public function getOneStatus($id)
    {
        $status = $this->findOne($id);

        if (!$status)
            $this->resourceNotFound();

        return $status;
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
    /**
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Resource not found or empty');
    }
}
