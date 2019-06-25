<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class RegionController extends AbstractController
{
    protected $entity = 'App\Entity\Region';
    protected $namespaceType = 'App\Form\RegionType';

    /**
     * Retrieve all data from one table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Route(
     *      name = "region_list",
     *      path = "/region",
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
     *  name="name",
     *  description="set your name of region you looking for"
     * )
     */
    public function getRegion(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($name = $paramFetcher->get('name'))
            $qb = $repository->filterWith($qb,$name, 'entity.name'); //Filtre selon le nom de l'évent
        
        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $region = $qb->getQuery()->getResult();

        if (!$region)
            $this->resourceNotFound();

        return $region;
    }
    
    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Get("/region/{id}")
     */
    public function getOneRegion($id)
    {
        $region = $this->findOne($id);

        if (!$region)
            $this->resourceNotFound();

        return $region;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Post("/region")
     */
    public function postRegion(Request $request)
    {
        $region = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite      

        $form = $this->createForm($this->namespaceType, $region);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        //dump($form); die;

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($region);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $region;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    /**
     * Update partial the resource
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Patch("/region/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $region = $this->findOne($request->get('id'));

        if (empty($region))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $region);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($region);
            $em->flush();

            return $region;
        }
        else
            return $form;
    }

    /**
     * Delete the resource
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Delete("/region/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $region = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($region)
        {
            $em->remove($region);
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
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Resource not found or empty');
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
