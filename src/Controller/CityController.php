<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CityController extends AbstractController
{
    protected $entity = 'App\Entity\City';
    protected $namespaceType = 'App\Form\CityType';
    
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Route(
     *      name = "city_list",
     *      path = "/city",
     *      methods = { Request::METHOD_GET }
     * )
     */
    public function getCity()
    {
        $cities =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$cities)
            $this->resourceNotFound();

        return $cities;
    }
    
    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Route(
     *      name = "city_one",
     *      path = "/city/{id}",
     *      methods = { Request::METHOD_GET }
     * )
     */
    public function getOneCity($id)
    {
        $city = $this->findOne($id);

        if (!$city)
            $this->resourceNotFound();

        return $city;
    }

    /**
     * Delete the resource
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Delete("/city/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($city)
        {
            $em->remove($city);
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
