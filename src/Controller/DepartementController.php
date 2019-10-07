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
     * @Rest\Route(
     *      name = "departement_list",
     *      path = "/departement",
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
     * @Rest\Get("/departement/{id}")
     *
     * @param $id
     * @return object|null
     */
    public function getOneDepartement($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Departement created")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Post("/departement")
     *
     * @param Request $request
     * @return FormInterface
     */
    public function postDepartement(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Departement")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Put("/departement/{id}")
     *
     * @param Request $request
     * @return object|FormInterface|null
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Departement")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Patch("/departement/{id}")
     *
     * @param Request $request
     * @return object|FormInterface|null
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=200, description="return the updated Departement")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Delete("/departement/{id}")
     *
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        return $this->delete($id);
    }

}
