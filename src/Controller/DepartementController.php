<?php

namespace App\Controller;

use App\Representation\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DepartementController
 * @package App\Controller
 * @SWG\Tag(name="Departement")
 */
class DepartementController extends DefaultController
{
    protected $entity = 'App\Entity\Departement';
    protected $namespaceType = 'App\Form\DepartementType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first city",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\City", groups={"all", "city"}))
     *     )
     * )
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Route(name = "departement_list", path = "/departement", methods = { Request::METHOD_GET })
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
     *
     * @param ParamFetcher $paramFetcher
     * @return Pagination
     */
    public function getDepartements(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Departement")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Get(path="/departement/{id}", name="GET_Departement", methods={Request::METHOD_GET})
     *
     * @param $id
     * @return object|null
     */
    public function getDepartement($id)
    {
        return $this->getOne($id);
    }
}
