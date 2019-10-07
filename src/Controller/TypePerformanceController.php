<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;

/**
 * Class TypePerformanceController
 * @package App\Controller
 * @SWG\Tag(name="TypePerformance")
 */
class TypePerformanceController extends AbstractController
{
    protected $entity = 'App\Entity\TypePerformance';
    protected $namespaceType = 'App\Form\TypePerformanceType';
    
    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first typePerformance",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\TypePerformance", groups={"all", "typePerformance"}))
     *     )
     * )
     * 
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Route(
     *      name = "typeperformance_list",
     *      path = "/typeperformance",
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
     */

    public function getTypePerformance(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity);
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $typePerformances = $qb->getQuery()->getResult();

        if (!$typePerformances)
            $this->resourceNotFound();

        return $typePerformances;
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the TypePerformance")
     * 
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Get("/typeperformance/{id}")
     */
    public function getOneTypePerformance($id)
    {
        $typePerformance = $this->findOne($id);

        if (!$typePerformance)
            $this->resourceNotFound();

        return $typePerformance;
    }

    /**
     * Create & persist a resource in database
     *
     * @SWG\Response(response=201, description="return the TypePerformance created")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Post("/typeperformance")
     */
    public function postTypePerformance(Request $request)
    {
        $typePerformance = new $this->entity();

        // creation d'un formulaire a partir de :
        // - modele de formulaire (informe la liste des champs du formulaire)
        // - sur lequelle, on mappe les proprietes de l'entite
        $form = $this->createForm($this->namespaceType, $typePerformance);

         // on envoie les donnees recuperees dans le corps de la requete HTTP
        $form->submit($request->request->all()); // Validation des données

        // si le formulaire est valide, on peut persister les donnees en base
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typePerformance);
            $em->flush();

            // succes : on renvoie la ressource que l'on vient de creer
            return $typePerformance;
        }
        else
            // echec : on renvoie le formulaire et les messages d'erreurs 
            return $form;
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated TypePerformance")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Put("/typeperformance/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated TypePerformance")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Patch("/typeperformance/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $typePerformance = $this->findOne($request->get('id'));

        if (empty($typePerformance))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $typePerformance);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($typePerformance);
            $em->flush();

            return $typePerformance;
        }
        else
            return $form;
    }


    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "typePerformance"})
     * @Rest\Delete("/typeperformance/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $typePerformance = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($typePerformance)
        {
            $em->remove($typePerformance);
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
