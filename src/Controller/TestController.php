<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class TestController extends AbstractController
{
    protected $entity = 'App\Entity\Test';
    protected $namespaceType = 'App\Form\TestType';

    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Route(
     *      name = "_list",
     *      path = "/test",
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
     *  name="age",
     *  requirements="\d+",
     *  description="set the age of the 'test' you desired"
     * )
     * 
     *  \|/  TEXTSEARCH \|/
     * 
     * @Rest\QueryParam(
     *  name="textSearch",
     *  description="define the text that we'll look for"
     * )
     */
    public function getTest(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($age = $paramFetcher->get('age'))
            $qb = $repository->filterWith($qb, $age, 'entity.age');

        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);
        
        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));
        
        $tests = $qb->getQuery()->getResult();

        if (!$tests)
            $this->resourceNotFound();

        return $tests;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/test/{id}")
     */
    public function getOneTest($id)
    {
        $test = $this->findOne($id);

        if (!$test)
            $this->resourceNotFound();

        return $test;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View()
     * @Rest\Post("/test")
     */
    public function postTest(Request $request)
    {
        $test = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $test);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($test);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $test;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/test/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    
    
    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/test/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $test = $this->findOne($request->get('id'));

        if (empty($test))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $test);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($test);
            $em->flush();

            return $test;
        }
        else
            return $form;
    }
    
    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/test/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $test = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($test)
        {
            $em->remove($test);
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
