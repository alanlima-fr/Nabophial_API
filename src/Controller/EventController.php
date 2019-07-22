<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class EventController extends AbstractController
{
    protected $entity = 'App\Entity\Event';
    protected $namespaceType = 'App\Form\EventType';

    /**
     * Retrieve all data from one table
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
     *  name="privateEvent",
     *  requirements="\d+",
     *  description="set your type of event is private or no"
     * )
     * 
     * @Rest\QueryParam(
     *  name="lieu",
     *  description="set your lieu where you looking for"
     * )
     * 
     * @Rest\QueryParam(
     *  name="nom",
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
     *  description="set your date of event you looking for"
     * )
     */
    public function getEvent(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($privateEvent = $paramFetcher->get('privateEvent'))
            $qb = $repository->filterWith($qb,$privateEvent, 'entity.privateEvent'); //Filtre si l'event et privé ou public

        if ($status = $paramFetcher->get('status'))
            $qb = $repository->filterWith($qb,$status, 'entity.status');  //Filtre selon le status de l'event 
        
        if ($lieu = $paramFetcher->get('lieu'))
            $qb = $repository->filterWith($qb,$lieu, 'entity.lieu'); //Filtre selon le lieu de l'évent

        if ($nom = $paramFetcher->get('nom'))
            $qb = $repository->filterWith($qb,$noms, 'entity.nom'); //Filtre selon le nom de l'évent
            
        if ($date = $paramFetcher->get('date'))
            $qb = $repository->filterWith($qb,$date, 'entity.beginTime'); //Filtre selon la date de DEBUT de l'évent
        

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $event = $qb->getQuery()->getResult();

        if (!$event)
            $this->resourceNotFound();

        return $event;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View(serializerGroups={"all", "event"})
     * @Rest\Get("/event/{id}")
     */
    public function getOneEvent($id)
    {
        $event = $this->findOne($id);

        if (!$event)
            $this->resourceNotFound();

        return $event;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View(serializerGroups={"all", "event"})})
     * @Rest\Post("/event")
     */
    public function postEvent(Request $request)
    {
        $event = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite      

        $form = $this->createForm($this->namespaceType, $event);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        //dump($form); die;

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $event;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    /**
     * Update partial the resource
     * 
     * @Rest\View(serializerGroups={"all", "event"})})
     * @Rest\Patch("/event/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $this->findOne($request->get('id'));

        if (empty($event))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $event);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($event);
            $em->flush();

            return $event;
        }
        else
            return $form;
    }

    /**
     * Delete the resource
     * 
     * @Rest\View(serializerGroups={"all", "event"})
     * @Rest\Delete("/event/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($event)
        {
            $em->remove($event);
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
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Event not found or empty');
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
