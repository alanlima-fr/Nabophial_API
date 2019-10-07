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
class StatusController extends DefaultController
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
       return $this->getAll();
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
        return $this->getOne($id);
    }
}
