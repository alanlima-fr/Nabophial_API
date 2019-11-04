<?php

namespace App\Controller;

use App\Entity\Performance;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PerformanceController
 * @package App\Controller
 * @SWG\Tag(name="Performance")
 */
class PerformanceController extends DefaultController
{
    protected $entity = 'App:Performance';
    protected $namespaceEntity = 'App\Entity\Performance';
    protected $namespaceType = 'App\Form\PerformanceType';

    /**
     * Retrieve all data from the Performance table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first performance",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Performance", groups={"all", "performance"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "performance"})
     * @Rest\Route(
     *      name = "performance_list",
     *      path = "/performance",
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
     *  \|/  FILTER \|/
     *
     * @Rest\QueryParam(
     *  name="sport",
     *  description="Give the ID's of the sport you desired"
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
    public function getPerformances(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the Performance table
     *
     * @SWG\Response(response=200, description="return the Performance")
     *
     * @Rest\View(serializerGroups={"all", "performance"})
     * @Rest\Get("/performance/{id}")
     *
     * @param $id
     * @return object|null
     */
    public function getPerformance($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Performance created")
     *
     * @Rest\View(serializerGroups={"all", "performance"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/performance")
     *
     * @param Request $request
     * @return FormInterface
     */
    public function postPerformance(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Performance")
     *
     * @Rest\View(serializerGroups={"all", "performance"})
     * @Rest\Put("/performance/{id}")
     *
     * @param Request $request
     * @return Performance|object|FormInterface|null
     */
    public function putPerformance(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Performance")
     *
     * @Rest\View(serializerGroups={"all", "performance"})
     * @Rest\Patch("/performance/{id}")
     *
     * @param Request $request
     * @return object|FormInterface|null
     */
    public function patchPerformance(Request $request)
    {
        return $this->update($request, false);
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "performance"})
     * @Rest\Delete("/performance/{id}")
     *
     * @param $id
     * @return mixed|void
     */
    public function deletePerformance($id)
    {
        return $this->delete($id);
    }

}
