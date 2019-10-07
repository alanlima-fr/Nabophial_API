<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegionController
 * @package App\Controller
 * @SWG\Tag(name="Region")
 */
class RegionController extends DefaultController
{
    protected $entity = 'App\Entity\Region';
    protected $namespaceType = 'App\Form\RegionType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first region",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Region", groups={"all", "region"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "region"})
     * @Rest\Route(
     *      name = "region_list",
     *      path = "/region",
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
     * @return \App\Representation\Pagination
     */
    public function getRegion(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Region")
     *
     * @Rest\View(serializerGroups={"all", "region"})
     * @Rest\Get("/region/{id}")
     *
     * @param $id
     * @return object|null
     */
    public function getOneRegion($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Region created")
     *
     * @Rest\View(serializerGroups={"all", "region"})
     * @Rest\Post("/region")
     *
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function postRegion(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Region")
     *
     * @Rest\View(serializerGroups={"all", "region"})
     * @Rest\Patch("/region/{id}")
     *
     * @param Request $request
     * @return object|\Symfony\Component\Form\FormInterface|null
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "region"})
     * @Rest\Delete("/region/{id}")
     *
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        return $this->delete($id);
    }

}
