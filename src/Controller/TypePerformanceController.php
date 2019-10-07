<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;

/**
 * Class TypePerformanceController
 * @package App\Controller
 * @SWG\Tag(name="TypePerformance")
 */
class TypePerformanceController extends DefaultController
{
    protected $entity = 'App\Entity\TypePerformance';
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
     *      name = "typeperformance_list",
     *      path = "/typeperformance",
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

    public function getTypePerformance(ParamFetcher $paramFetcher)
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
     * @Rest\Get("/typeperformance/{id}")
     */
    public function getOneTypePerformance($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the TypePerformance created")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Post("/typeperformance")
     */
    public function postTypePerformance(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated TypePerformance")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Put("/typeperformance/{id}")
     * @param Request $request
     * @return \App\Entity\TypePerformance|object|\Symfony\Component\Form\FormInterface|null
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated TypePerformance")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Patch("/typeperformance/{id}")
     * @param Request $request
     * @return \App\Entity\TypePerformance|object|\Symfony\Component\Form\FormInterface|null
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
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Delete("/typeperformance/{id}")
     * 
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        return $this->delete($id);
    }
}
