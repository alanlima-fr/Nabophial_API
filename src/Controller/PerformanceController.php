<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;

class PerformanceController extends AbstractController
{
    protected $entity = 'App\Entity\Performance';
    protected $namespaceType = 'App\Form\PerformanceType';

    /**
     * Recupere tous les donnees de la table Performance
     * 
     * @Rest\View()
     * @Rest\Get("/performance")
     */
    public function getPerformance(ParamFetcher $paramFetcher)
    {
        $performances = $this->getDoctrine()->getRepository($this->entity);
        $qb - $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        if ($name = $paramFetcher->get('name'))
            $qb = $repository->filterWith($qb, $name, 'entity.name');
        
        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);

        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));

        $performances = $qb->getQuery()->getResult();

        if (!$performances)
            $this->resourceNotFound();

        return $performances;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
     * @Rest\Get("/performance/{id}")
     */
    public function getOnePerformance($id)
    {
        $performance = $this->findOne($id);

        if (!$performance)
            $this->resourceNotFound();
        return $performance;
    }
    

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/performance/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    

    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/performance/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $performance = $this->findOne($request->get('id'));

        if (empty($performance))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $performance);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing); // Validation des données
        
        if($form->isSubmitted() && $form->isValid())
        {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($performance);
            $em->flush();

            return $performance;
        }
        else
            return $form;
    }


    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/performance/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $performance = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($performance)
        {
            $em->remove($performance);
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
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Resource not found');
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
