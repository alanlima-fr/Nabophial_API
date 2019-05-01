<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class ImgController extends AbstractController
{
    protected $entity = 'App\Entity\Img';
    protected $namespaceType = 'App\Form\ImgType';
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Get("/img")
     */
    public function getImg()
    {
        $img =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$img)
            $this->resourceNotFound();

        return $img;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/img/{id}")
     */
    public function getOneImg($id)
    {
        $img = $this->findOne($id);

        if (!$img)
            $this->resourceNotFound();

        return $img;
    }

/**
     * Create & persist a resource in database
     * 
     * @Rest\View()
     * @Rest\Post("/img")
     */
    public function postImg(Request $request)
    {
        $img = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $img);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($img);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $img;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/img/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    } 
       
    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/img/{id}")
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
     * @Rest\Delete("/img/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $img = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($img)
        {
            $em->remove($img);
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
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Picture not found');
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
