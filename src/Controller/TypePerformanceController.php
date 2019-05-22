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
     * Retrieve all data from one table
     * 
     * @Rest\View()
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
     *  \|/  FILTER \|/
     * 
     * @Rest\QueryParam(
     *  name="name",
     *  requirements="\d+",
     *  description="set the name of the 'typeperformance' you desired"
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
        $repository = $this->getDoctrine()->getRepository($this->entity);
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        if ($name = $paramFetcher->get('name'))
            $qb = $repository->filterWith($qb, $name, 'entity.name');
    
        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);

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
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $typePerformance = $this->findOne($request->get('id'));

        if (empty($typePerformance))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $typePerformance);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($typePerformance);
            $em->flush();

            return $typePerformance;
        }
        else
            return $form;
    }


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
