<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class PlaceController extends AbstractController
{
    protected $entity = 'App\Entity\Place';
    protected $namespaceType = 'App\Form\PlaceType';
    
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Route(
     *      name = "place_list",
     *      path = "/place",
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
     *  \|/  TEXTSEARCH \|/
     * 
     * @Rest\QueryParam(
     *  name="textSearch",
     *  description="define the text that we'll look for"
     * )
     */
    public function getPlace(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb,$textSearch);  //Filtre selon l'adresse recherché
        
        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $place = $qb->getQuery()->getResult();

        if (!$place)
            $this->resourceNotFound();

        return $place;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Get("/place/{id}")
     */
    public function getOnePlace($id)
    {
        $place = $this->findOne($id);

        if (!$place)
            $this->resourceNotFound();

        return $place;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Post("/place")
     */
    public function postPlace(Request $request)
    {
        $place = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $place);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $place;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    /**
     * Update complete the resource
     * 
    * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Put("/place/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    
    
    /**
     * Update partial the resource
     * 
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Patch("/place/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $place = $this->findOne($request->get('id'));

        if (empty($place))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $place);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($place);
            $em->flush();

            return $place;
        }
        else
            return $form;
    }
    
    /**
     * Delete the resource
     * 
     * @Rest\View(serializerGroups={"all", "place"})
     * @Rest\Delete("/place/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $place = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($place)
        {
            $em->remove($place);
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
