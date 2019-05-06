<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class TypePerformanceController extends AbstractController
{
    protected $entity = 'App\Entity\TypePerformance';
    protected $namespace = 'App\Form\TypePerformanceType';
    
    /**
     * Recupere tous les donnees de la table TypePerformance
     * 
     * @Rest\View()
     * @Rest\Get("/typeperformance")
     */
    public function getTypePerformance(ParamFetcher $paramFetcher)
    {
        $typePerformances = $this->getDoctrine()->getRepository($this->entity);
        $qb - $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

/* ---------------
if ($ = $paramFetcher->get(''))
$qb = $repository->filterWith($qb, $, 'entity.');

if ($textSearch = $paramFetcher->get('textSearch'))
$qb = $repository->prepTextSearch($qb, $textSearch);
--------------- */

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $typePerformances = $qb->getQuery()->getResult();

        if (!$typePerformances)
            $this->resourceNotFound();

        return $typePerformances;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/typeperformance/{id}")
     */
    public function getOneTypePerformance($id)
    {
        $typePerformance = $this->findOne($id);

        if (!$typePerformance)
            $this->resourceNotFound();

        return $typePerformance;
    }

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/typeperformance/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    

    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/typeperformance/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
/**
 *  protected function update
 */


    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/typeperformance/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $typePerformance = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($typePerformance)
        {
            $em->remove($typePerformance);
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
