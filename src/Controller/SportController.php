<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SportController
 * @package App\Controller
 * @SWG\Tag(name="Sport")
 */
class SportController extends DefaultController
{
    protected $entity = 'App:Sport';
    protected $namespaceEntity = 'App\Entity\Sport';
    protected $namespaceType = 'App\Form\SportType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first sport",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Sport", groups={"all", "sport"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Route(
     *      name = "sport_list",
     *      path = "/sport",
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
     * @param ParamFetcher $paramFetcher
     * @return
     */
    public function getSports(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Sport")
     *
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Get("/sport/{id}")
     *
     * @param $id
     * @return object|null
     */
    public function getSport($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Sport created")
     *
     * @Rest\View(serializerGroups={"all", "sport"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/sport")
     *
     * @param Request $request
     * @return FormInterface
     */
    public function postSport(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Sport")
     *
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Put("/sport/{id}")
     *
     * @param Request $request
     * @return object|FormInterface|null
     */
    public function putSport(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Sport")
     *
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Patch("/sport/{id}")
     *
     * @param Request $request
     * @return object|FormInterface|null
     */
    public function patchSport(Request $request)
    {
        return $this->update($request, false);
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Delete("/sport/{id}")
     *
     * @param $id
     * @return mixed|void
     */
    public function deleteSport($id)
    {
        return $this->delete($id);
    }

}
