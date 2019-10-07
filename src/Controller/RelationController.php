<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RelationController
 * @package App\Controller
 * @SWG\Tag(name="Relation")
 */
class RelationController extends DefaultController
{
    protected $entity = 'App\Entity\Relation';
    protected $namespaceType = 'App\Form\RelationType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first relation",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Relation", groups={"all", "relation"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Route(
     *      name = "relation_list",
     *      path = "/relation",
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
     *  name="idUser",
     *  requirements="\d+",
     *  description="set your type of event is private or no"
     * )
     *
     */
    public function getRelation(ParamFetcher $paramFetcher)
    {

        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($idUser = $paramFetcher->get('idUser'))
            $qb = $repository->filterWith($qb, $idUser, 'entity.idUser'); //Filtre pour l'idUser recherché


        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $relation = $qb->getQuery()->getResult();

        if (!$relation)
            $this->resourceNotFound();

        return $relation;
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
     * @SWG\Response(response=200, description="return the Relation")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
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
     * @SWG\Response(response=201, description="return the Relation created")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
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
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($relation);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $relation;
        } else
            // echec : on renvoie le formulaire et les messages d'erreurs
            return $form;
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Relation")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Put("/relation/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
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

        if ($form->isSubmitted() && $form->isValid()) {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($relation);
            $em->flush();

            return $relation;
        } else
            return $form;
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Relation")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Patch("/relation/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "relation"})
     * @Rest\Delete("/relation/{id}")
     * @param $id
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $relation = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);

        if ($relation) {
            $em->remove($relation);
            $em->flush();
        } else
            $this->resourceNotFound();
    }
}
