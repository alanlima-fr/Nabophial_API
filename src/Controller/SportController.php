<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class SportController extends AbstractController
{
    protected $entity = 'App\Entity\Sport';
    protected $namespaceType = 'App\Form\SportType';
    /**
    * Retrieve all data from one table
    *
    * @Rest\View()
    * @Rest\Get("/sport")
    */
    public function getSport(ParamFetcher $paramFetcher)
    {
    $sports = $this->getDoctrine()->getRepository($this->entity);
    $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));
    /* ---------------
    if ($ = $paramFetcher->get(''))
    $qb = $repository->filterWith($qb, $, 'entity.');

    if ($textSearch = $paramFetcher->get('textSearch'))
    $qb = $repository->prepTextSearch($qb, $textSearch);


    --------------- */
    $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

    $sports = $qb->getQuery()->getResult();

    if (!$sports)
    $this->resourceNotFound();

    return $sports;
    }

    /**
    * Retrieve one resource from the table
    *
    * @Rest\View()
    * @Rest\Get("/sport/{id}")
    */
    public function getOneSport($id)
    {
    $sport = $this->findOne($id);

    if (!$sport)
    $this->resourceNotFound();

    return $sport;
    }


    /* ------------

    A rajouter :
    - create & persist a resource in db

    - update complete the resource

    - update partial the resource

    -------------- */




    /**
    * Delete the resource
    *
    * @Rest\View()
    * @Rest\Delete("/sport/{id}")
    */
    public function delete($id)
    {
    $em = $this->getDoctrine()->getManager();
    $sport = $this->getDoctrine()
    ->getRepository($this->entity)
    ->find($id);
    if($sport)
    {
    $em->remove($sport);
    $em->flush();
    }
    else
    $this->resourceNotFound();
    }

    /**
    * Return Error in case of a not found.
    */
    protected function resourceNotFound()
    {
    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Resource not found');
    }

    /**
    * Return a resource by his id.
    */
    protected function findOne($id)
    {
    return $this->getDoctrine()
    ->getRepository($this->entity)
    ->find($id);
    }

}
