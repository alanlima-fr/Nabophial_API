<?php

namespace App\Controller;

use App\Entity\City;
use App\Representation\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CityController
 * @package App\Controller
 * @SWG\Tag(name="City")
 */
class CityController extends DefaultController
{
    protected $entity = 'App\Entity\City';
    protected $namespaceType = 'App\Form\CityType';

    /**
     * Retrieve all data from the city table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first city",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\City", groups={"all", "city"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "city"})
     * @Rest\Route(
     *      name = "city_list",
     *      path = "/city",
     *      methods = { Request::METHOD_GET }
     * )
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
     * @param ParamFetcher $paramFetcher
     * @return Pagination
     */
    public function getCities(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the city table
     *
     * @SWG\Response(response=200, description="return the City")
     *
     * @Rest\View(serializerGroups={"all", "city"})
     * @Rest\Route(
     *      name = "city_one",
     *      path = "/city/{id}",
     *      methods = { Request::METHOD_GET }
     * )
     * @param $id
     * @return object|null
     */
    public function getCity($id)
    {
        return $this->getOne($id);
    }
}
