<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TypePerformanceController
 * @package App\Controller
 * @SWG\Tag(name="TypePerformance")
 */
class TypePerformanceController extends DefaultController
{
    protected $entity = 'App:TypePerformance';
    protected $namespaceEntity = 'App\Entity\TypePerformance';
    protected $namespaceType = 'App\Form\TypePerformanceType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first typePerformance",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\TypePerformance", groups={"all", "typePerformance"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Route(
     *      name = "GET_typePerformance_list",
     *      path = "/typePerformance",
     *      methods = { Request::METHOD_GET }
     * )
     *
     * QUERY PARAM ***
     *
     *  \|/  SORT   \|/
     *
     * @Rest\QueryParam(
     *  name="sortBy",
     *  default="id",
     *  description="define the sort"
     * )
     * @Rest\QueryParam(
     *  name="sortOrder",
     *  default="desc",
     *  description="define the order of the sort"
     * )
     *
     *  \|/  PAGINATION \|/
     *
     * @Rest\QueryParam(
     *  name="page",
     *  requirements="\d+",
     *  default=1,
     *  description="Paging start index(depends on the limit)"
     * )
     * @Rest\QueryParam(
     *  name="limit",
     *  requirements="\d+",
     *  default=25,
     *  description="Number of items to display. affects pagination"
     * )
     *
     *  \|/  TEXTSEARCH \|/
     *
     * @Rest\QueryParam(
     *  name="textSearch",
     *  description="define the text that we'll look for"
     * )
     */

    public function getTypePerformances(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the TypePerformance")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Get(path="/typePerformance/{id}", name="GET_TypePerformance", methods={Request::METHOD_GET})
     *
     * @param $id
     * @return object|null
     */
    public function getTypePerformance($id)
    {
        return $this->getOne($id);
    }
}
