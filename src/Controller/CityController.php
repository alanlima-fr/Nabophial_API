<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CityController
 * @package App\Controller
 * @SWG\Tag(name="City")
 */
class CityController extends AbstractController
{
    protected $entity = 'App\Entity\City';
    protected $namespaceType = 'App\Form\CityType';

    /**
     * Retrieve all data from the city table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first city",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\City", groups={"all", "city"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "city"})
     * @Rest\Route(
     *      name = "city_list",
     *      path = "/city",
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
    public function getCity(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);  //Cherche le nom de la ville dans l'entité city, départment ou region

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $city = $qb->getQuery()->getResult();

        if (!$city)
            $this->resourceNotFound();

        return $city;
    }

    /**
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new NotFoundHttpException('Resource not found or empty');
    }

    /**
     * Retrieve one resource from the city table
     *
     * @SWG\Response(response=200, description="return the City")
     *
     * @Rest\View(serializerGroups={"all", "city"})
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
     * @SWG\Response(response=201, description="return the City created")
     *
     * @Rest\View(serializerGroups={"all", "city"})
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
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $city;
        } else
            // echec : on renvoie le formulaire et les messages d'erreurs
            return $form;
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated City")
     *
     * @Rest\View(serializerGroups={"all", "city"})
     * @Rest\Put("/city/{id}")
     *
     * @param Request $request
     * @return \App\Entity\City|object|\Symfony\Component\Form\FormInterface|null
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated City")
     *
     * @Rest\View(serializerGroups={"all", "city"})
     * @Rest\Patch("/city/{id}")
     *
     * @param Request $request
     * @return \App\Entity\City|object|\Symfony\Component\Form\FormInterface|null
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

        if ($form->isSubmitted() && $form->isValid()) {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($city);
            $em->flush();

            return $city;
        } else
            return $form;
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "city"})
     * @Rest\Delete("/city/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);

        if ($city) {
            $em->remove($city);
            $em->flush();
        } else
            $this->resourceNotFound();
    }

}
