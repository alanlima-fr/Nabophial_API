<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    protected $entity = 'App\Entity\Event';
    protected $namespaceType = 'App\Form\EventType';

    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Get("/event")
     */
    public function getEvent()
    {
        $event =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$event)
            $this->resourceNotFound();

        return $event;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
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
     * @Rest\View()
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
     * @Rest\View()
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
     * @Rest\View()
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
