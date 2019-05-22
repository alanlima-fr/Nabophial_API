<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class PerformanceController extends AbstractController
{
    protected $entity = 'App\Entity\Performance';
    protected $namespaceType = 'App\Form\PerformanceType';

/**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Route(
     *      name = "_list",
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
     *  name="name",
     *  requirements="\d+",
     *  description="set the name of the 'performance' you desired"
     * )
     * 
     *  \|/  TEXTSEARCH \|/
     * 
     * @Rest\QueryParam(
     *  name="textSearch",
     *  description="define the text that we'll look for"
     * )
     */

    public function getPerformance(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity);
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        if ($name = $paramFetcher->get('name'))
            $qb = $repository->filterWith($qb, $name, 'entity.name');
        
        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $performances = $qb->getQuery()->getResult();

        if (!$performances)
            $this->resourceNotFound();

        return $performances;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/performance/{id}")
     */
    public function getOnePerformance($id)
    {
        $performance = $this->findOne($id);

        if (!$performance)
            $this->resourceNotFound();
        return $performance;
    }
   
/**
     * Create & persist a resource in database
     * 
     * @Rest\View()
     * @Rest\Post("/performance")
     */
    public function postPerformance(Request $request)
    {
        $performance = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $performance);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($performance);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $performance;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }


    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/performance/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    

    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/performance/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $performance = $this->findOne($request->get('id'));

        if (empty($performance))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $performance);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($performance);
            $em->flush();

            return $performance;
        }
        else
            return $form;
    }


    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/performance/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $performance = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($performance)
        {
            $em->remove($performance);
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
