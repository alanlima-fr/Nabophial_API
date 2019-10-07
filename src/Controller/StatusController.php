<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;

/**
 * Class StatusController
 * @package App\Controller
 * @SWG\Tag(name="Status")
 */
class StatusController extends AbstractController
{
    protected $entity = 'App\Entity\Status';
    protected $namespaceType = 'App\Form\StatusType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first status",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Status", groups={"all", "status"}))
     *     )
     * )
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
     * @SWG\Response(response=200, description="return the Status")
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
