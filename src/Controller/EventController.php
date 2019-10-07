<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventController
 * @package App\Controller
 * @SWG\Tag(name="Event")
 */
class EventController extends DefaultController
{
    protected $entity = 'App\Entity\Event';
    protected $namespaceType = 'App\Form\EventType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first Event",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Event", groups={"all", "event"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "event"})
     * @Rest\Route(
     *      name = "event_list",
     *      path = "/event",
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
     *  name="private",
     *  description="set your type of event is private or no"
     * )
     *
     * @Rest\QueryParam(
     *  name="lieu",
     *  description="set your lieu where you looking for"
     * )
     *
     * @Rest\QueryParam(
     *  name="text",
     *  description="set your nom of event you looking for"
     * )
     *
     * @Rest\QueryParam(
     *  name="status",
     *  description="set your status of event you looking for"
     * )
     *
     * @Rest\QueryParam(
     *  name="date",
     *  description="set your date of begin of event you looking for"
     * )
     * @param ParamFetcher $paramFetcher
     * @return Object
     */
    public function getEvent(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($text = $paramFetcher->get('text'))
            $qb = $repository->prepTextSearch($qb, $text); //Filtre selon le nom  ou la descripstion de l'évent

        if ($lieu = $paramFetcher->get('lieu'))
            $qb = $repository->prepTextSearch($qb, $lieu, 'lieu'); //Filtre selon le lieu de l'évent

        if ($lieu = $paramFetcher->get('date'))
            $qb = $repository->prepTextSearch($qb, $lieu, 'date'); //Filtre selon la date de l'évent

        if ($private = $paramFetcher->get('private'))
            $qb = $repository->checkBoolSql($qb, $private); // Filtre selon le type d'évenment (privé ou public)

        if ($status = $paramFetcher->get('status'))
            $qb = $repository->filterWith($qb, $status, 'entity.status'); //Filtre selon le status de l'évenement

        return $this->paginate($qb,
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Event")
     *
     * @Rest\View(serializerGroups={"all", "event"})
     * @Rest\Get("/event/{id}")
     *
     * @param Int $id
     * @return object|null
     */
    public function getOneEvent(Int $id)
    {
        return $this->getOne($id);
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Event created")
     *
     * @Rest\View(serializerGroups={"all", "event"})
     * @Rest\Post("/event")
     *
     * @param Request $request
     * @return FormInterface
     */
    public function postEvent(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated City")
     *
     * @Rest\View(serializerGroups={"all", "event"})
     * @Rest\Patch("/event/{id}")
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
     * @Rest\View(serializerGroups={"all", "event"})
     * @Rest\Delete("/event/{id}")
     */
    public function delete($id)
    {
        return $this->delete($id);
    }
}
