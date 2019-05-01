<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TypePerformanceController extends AbstractController
{
    /**
     * @Route("/typePerformance", name="type_performance")
     */
    public function index()
    {
        return $this->render('type_performance/index.html.twig', [
            'controller_name' => 'typePerformanceController',
        ]);
    }

    protected $entity = 'App\Entity\TypePerformance';
    /**
     * Recupere tous les donnees de la table TypePerformance
     * 
     * @Rest\View()
     * @Rest\Get("/typeperformance")
     */
    public function getTypePerformance()
    {
        $typePerformances = $this->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();

        if (!$typePerformances)
            $this->resourceNotFound();
        return $typePerformances;
    }

    /**
     * Retrieve one resource from the table
     * 
     * @Rest\View()
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
     * Delete the resource
     * 
     * @Rest\View()
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
