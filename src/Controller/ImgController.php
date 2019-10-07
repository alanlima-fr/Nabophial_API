<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;

/**
 * Class ImgController
 * @package App\Controller
 * @SWG\Tag(name="Img")
 */
class ImgController extends AbstractController
{
    protected $entity = 'App\Entity\Img';
    protected $namespaceType = 'App\Form\ImgType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first img",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\Img", groups={"all", "img"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Route(
     *      name = "img_list",
     *      path = "/img",
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
     */
    public function getImg(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées

        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $img = $qb->getQuery()->getResult();

        if (!$img)
            $this->resourceNotFound();

        return $img;
    }

    /**
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new NotFoundHttpException('Picture not found');
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the Img")
     *
     * @Rest\View(serializerGroups={"all", "img"})
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
     * @SWG\Response(response=201, description="return the Img created")
     *
     * @Rest\View(serializerGroups={"all", "img"})
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
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($img);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $img;
        } else
            // echec : on renvoie le formulaire et les messages d'erreurs
            return $form;
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated Img")
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Put("/img/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated Img")
     *
     * @Rest\View(serializerGroups={"all", "img"})
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

        if ($form->isSubmitted() && $form->isValid()) {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($test);
            $em->flush();

            return $test;
        } else
            return $form;
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "img"})
     * @Rest\Delete("/img/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $img = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);

        if ($img) {
            $em->remove($img);
            $em->flush();
        } else
            $this->resourceNotFound();
    }

}
