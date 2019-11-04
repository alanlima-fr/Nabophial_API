<?php

namespace App\Controller;

use App\Entity\Place;
use App\Representation\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlaceController
 * @package App\Controller
 * @SWG\Tag(name="Place")
 */
class PlaceController extends DefaultController
{
    protected $entity = 'App:Place';
    protected $namespaceEntity = 'App\Entity\Place';
    protected $namespaceType = 'App\Form\PlaceType';

    /**
     * Retrieve all data from the Place table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first place",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Place", groups={"all", "place"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Route(
     *      name = "place_list",
     *      path = "/place",
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
    public function getPlaces(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the place table
     *
     * @SWG\Response(response=200, description="return the Place")
     *
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Get("/place/{id}")
     *
     * @param $id
     * @return object|null
     */
    public function getPlace($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Place created")
     *
     * @Rest\View(serializerGroups={"all", "place"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/place")
     *
     * @param Request $request
     * @return FormInterface
     */
    public function postPlace(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Place")
     *
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Put("/place/{id}")
     *
     * @param Request $request
     * @return object|FormInterface|null
     */
    public function putPlace(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Place")
     *
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Patch("/place/{id}")
     *
     * @param Request $request
     * @return Place|object|FormInterface|null
     */
    public function patchPlace(Request $request)
    {
        return $this->update($request, false);
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Delete("/place/{id}")
     *
     * @param $id
     * @return mixed|void
     */
    public function deletePlace($id)
    {
        return $this->delete($id);
    }
}
