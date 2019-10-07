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
 * Class ImgController
 * @package App\Controller
 * @SWG\Tag(name="Img")
 */
class ImgController extends DefaultController
{
    protected $entity = 'App\Entity\Img';
    protected $namespaceType = 'App\Form\ImgType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first img",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Img", groups={"all", "img"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Route(
     *      name = "img_list",
     *      path = "/img",
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
     * @param ParamFetcher $paramFetcher
     * @return Pagination
     */
    public function getImg(ParamFetcher $paramFetcher)
    {
        return $this->paginate($this->createQB($paramFetcher),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Img")
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Get("/img/{id}")
     *
     * @param $id
     * @return object|null
     */
    public function getOneImg($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Img created")
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Post("/img")
     *
     * @param Request $request
     * @return FormInterface
     */
    public function postImg(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Img")
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Put("/img/{id}")
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
     * @SWG\Response(response=200, description="return the updated Img")
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Patch("/img/{id}")
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
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Delete("/img/{id}")
     *
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        return $this->delete($id);
    }

}
