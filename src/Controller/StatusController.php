<?php

namespace App\Controller;

use FOS\RestBundle\Request\ParamFetcher;
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
    protected $entity = 'App:Status';
    protected $namespaceEntity = 'App\Entity\Status';
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
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default=1, description="Paging start index (depends on the limit)")
     * @Rest\QueryParam(name="limit", requirements="\d+", default=25, description="Number of resource displayed. affects Pagination")
     *
     * @param ParamFetcher $paramFetcher
     * @return \App\Representation\Pagination
     */
    public function getStatus(ParamFetcher $paramFetcher)
    {
       return $this->getAll($paramFetcher);
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Status")
     *
     * @Rest\View(serializerGroups={"all", "status"})
     * @Rest\Get("/status/{id}")
     *
     * @param $id
     * @return object|null
     */
    public function getOneStatus($id)
    {
        return $this->getOne($id);
    }
}
