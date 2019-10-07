<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;

/**
 * Class DepartementController
 * @package App\Controller
 * @SWG\Tag(name="Departement")
 */
class DepartementController extends AbstractController
{
    protected $entity = 'App\Entity\Departement';
    protected $namespaceType = 'App\Form\DepartementType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first city",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\City", groups={"all", "city"}))
     *     )
     * )
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Route(
     *      name = "departement_list",
     *      path = "/departement",
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
    public function getDepartement(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch); //Cherche le nom du départment ou de la region

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $departement = $qb->getQuery()->getResult();

        if (!$departement)
            $this->resourceNotFound();

        return $departement;
    }

    /**
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new NotFoundHttpException('Resource not found or empty');
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Departement")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Get("/departement/{id}")
     */
    public function getOneDepartement($id)
    {
        $departement = $this->findOne($id);

        if (!$departement)
            $this->resourceNotFound();

        return $departement;
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

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the Departement created")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Post("/departement")
     */
    public function postDepartement(Request $request)
    {
        $departement = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite

        $form = $this->createForm($this->namespaceType, $departement);

        // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        //dump($form); die;

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($departement);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $departement;
        } else
            // echec : on renvoie le formulaire et les messages d'erreurs
            return $form;
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Departement")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Patch("/departement/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }

    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $departement = $this->findOne($request->get('id'));

        if (empty($departement))
            $this->resourceNotFound();

        $form = $this->createForm($this->namespaceType, $departement);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données

        if ($form->isSubmitted() && $form->isValid()) {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($departement);
            $em->flush();

            return $departement;
        } else
            return $form;
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=200, description="return the updated Departement")
     *
     * @Rest\View(serializerGroups={"all", "departement"})
     * @Rest\Delete("/departement/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $departement = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);

        if ($departement) {
            $em->remove($departement);
            $em->flush();
        } else
            $this->resourceNotFound();
    }

}
