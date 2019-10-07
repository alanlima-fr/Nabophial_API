<?php

namespace App\Controller;

use App\Entity\Relation;
use App\Representation\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RelationController
 * @package App\Controller
 * @SWG\Tag(name="Relation")
 */
class RelationController extends DefaultController
{
    protected $entity = 'App\Entity\Relation';
    protected $namespaceType = 'App\Form\RelationType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first relation",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Relation", groups={"all", "relation"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Route(
     *      name = "relation_list",
     *      path = "/relation",
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
     *  \|/  FILTER \|/
     *
     * @Rest\QueryParam(
     *  name="idUser",
     *  requirements="\d+",
     *  description="set your type of event is private or no"
     * )
     * @param ParamFetcher $paramFetcher
     * @return Pagination
     */
    public function getRelation(ParamFetcher $paramFetcher)
    {
        // On récupère le repository ou nos fonctions sql sont rangées
        $repository = $this->getDoctrine()->getRepository($this->entity);
        // On récupère la QueryBuilder instancié dans la fonctions
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        if ($idUser = $paramFetcher->get('idUser'))
            $qb = $repository->filterWith($qb, $idUser, 'entity.idUser'); //Filtre pour l'idUser recherché

        return $this->paginate($qb,
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Relation")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Get("/relation/{id}")
     * @param $id
     * @return Relation|object|null
     */
    public function getOneRelation($id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Relation created")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Post("/relation")
     *
     * @param Request $request
     * @return FormInterface
     */
    public function postRelation(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Relation")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Put(path="/relation/{id}", name="PUT_relation", methods={Request::METHOD_PUT})
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
     * @SWG\Response(response=200, description="return the updated Relation")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Patch("/relation/{id}")
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
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Delete(path="/relation/{id}", name="DELETE_relation", methods={Request::METHOD_DELETE})
     *
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        return $this->delete($id);
    }
}
