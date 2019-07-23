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
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Route(
     *      name = "sport_list",
     *      path = "/sport",
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
     *  \|/  TEXTSEARCH \|/
     * 
     * @Rest\QueryParam(
     *  name="textSearch",
     *  description="define the text that we'll look for"
     * )
     * 
     */
    public function getSport(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity);
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);
                    
        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $sport = $qb->getQuery()->getResult();

        if (!$sport)
            $this->resourceNotFound();

        return $sport;
    }

    /**
    * Retrieve one resource from the table
    *
    * @Rest\View(serializerGroups={"all", "sport"})
    * @Rest\Get("/sport/{id}")
    */
    public function getOneSport($id)
    {
        $sport = $this->findOne($id);

        if (!$sport)
            $this->resourceNotFound();

        return $sport;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Post("/sport")
     */
    public function postSport(Request $request)
    {
        $sport = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $sport);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sport);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $sport;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }


    /**
     * Update complete the resource
     * 
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Put("/sport/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    
    
    /**
     * Update partial the resource
     * 
     * @Rest\View(serializerGroups={"all", "sport"})
     * @Rest\Patch("/sport/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }

    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $sport = $this->findOne($request->get('id'));

        if (empty($sport))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $sport);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($sport);
            $em->flush();

            return $sport;
        }
        else
            return $form;
    }

    /**
    * Delete the resource
    *
    * @Rest\View(serializerGroups={"all", "sport"})
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
