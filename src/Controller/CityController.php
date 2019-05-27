<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CityController extends AbstractController
{
    protected $entity = 'App\Entity\City';
    
    /**
     * Retrieve all data from one table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Route(
     *      name = "city_list",
     *      path = "/city",
     *      methods = { Request::METHOD_GET }
     * )
     */
    public function getCity()
    {
        $cities =  $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$cities)
            $this->resourceNotFound();

        return $cities;
    }
    
    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View(serializerGroups={"all"})
     * @Rest\Route(
     *      name = "city_one",
     *      path = "/city/{id}",
     *      methods = { Request::METHOD_GET }
     * )
     */
    public function getOneCity($id)
    {
        $city = $this->findOne($id);

        if (!$city)
            $this->resourceNotFound();

        return $city;
    }

    /**
     * Create & persist a resource in database
     * 
     * @Rest\View()
     * @Rest\Post("/city")
     */
    public function postCity(Request $request)
    {
        $city = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $city);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $city;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/city/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    
    
    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/city/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->findOne($request->get('id'));

        if (empty($city))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $city);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($city);
            $em->flush();

            return $city;
        }
        else
            return $form;
    }
    
    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/city/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($city)
        {
            $em->remove($city);
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
