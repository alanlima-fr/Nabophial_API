<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RelationController extends AbstractController
{
    protected $entity = 'App\Entity\Relation';
    protected $namespaceType = 'App\Form\RelationType';
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Get("/relation")
     */
    public function getRelation()
    {
        $relation =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$relation)
            $this->resourceNotFound();

        return $relation;
    }

       /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/relation/{id}")
     */
    public function getOneRelation($id)
    {
        $relation = $this->findOne($id);

        if (!$relation)
            $this->resourceNotFound();

        return $relation;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View()
     * @Rest\Post("/relation")
     */
    public function postRelation(Request $request)
    {
        $relation = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $relation);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($relation);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $relation;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    } 

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/relation/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/relation/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $relation = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($relation)
        {
            $em->remove($relation);
            $em->flush();
        }
        else
            $this->resourceNotFound();
    }

    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/relation/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $relation = $this->findOne($request->get('id'));

        if (empty($relation))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $relation);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($relation);
            $em->flush();

            return $relation;
        }
        else
            return $form;
    }

    /**
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Relation not found');
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
