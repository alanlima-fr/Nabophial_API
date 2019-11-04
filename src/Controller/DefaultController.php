<?php

namespace App\Controller;

use App\Representation\Pagination;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Request\ParamFetcher;
use LogicException;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends AbstractController
{
    /**
     * Exemple : 'App:AppUser'
     *
     * @var String
     */
    protected $entity;

    /**
     * Namespace a importer pour utiliser Doctrine
     * Example : 'App\Entity\AppUser'
     *
     * @var String
     */
    protected $namespaceEntity;

    /**
     * Namespace we need import to use Form Class
     * Example : 'App\Form\AppUserType'
     *
     * @var String
     */
    protected $namespaceType;

    /**
     * Retrieve All
     * @param ParamFetcher $paramFetcher
     * @return Pagination
     */
    protected function getAll(ParamFetcher $paramFetcher)
    {
        $qb = $this->getDoctrine()
            ->getRepository($this->entity)
            ->createQueryBuilder('entity');

        return $this->paginate($qb,
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
    }

    /**
     * @param QueryBuilder $qb
     * @param int $limit
     * @param int $page
     * @return Pagination
     */
    protected function paginate(QueryBuilder $qb, $limit = 25, $page = 1)
    {
        if (0 == $limit || 0 == $page) {
            throw new LogicException('$limit & $page must be greater than 0.');
        }

        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pager->setMaxPerPage((int)$limit);
        $pager->setCurrentPage($page);

        return new Pagination($pager);
    }

    /**
     * @param $id
     * @return object|null
     */
    protected function getOne($id)
    {
        return $this->findOneBy($id);
    }

    /**
     * @param $id
     * @return object|null
     */
    protected function findOneBy($id)
    {
        $resource = $this->getDoctrine()->getRepository($this->entity)->find($id);
        if (!$resource || empty($resource)) {
            $this->resourceNotFound();
        }
        return $resource;
    }

    protected function resourceNotFound()
    {
        throw new NotFoundHttpException('Resource not found');
    }

    protected function post(Request $request)
    {
        $resource = new $this->namespaceEntity();

        $form = $this->createForm($this->namespaceType, $resource);
        $form->submit($request->request->all());

        
        if ($form->isValid() && $form->isSubmitted()) {
            if ($resource)
                $em = $this->getDoctrine()->getManager();
            $em->persist($resource);
            $em->flush();

            return $resource;
        }

        return $form;
    }

    protected function put(Request $request)
    {
        return $this->update($request, true);
    }

    protected function update(Request $request, $clearMissing)
    {
        $resource = $this->findOneBy($request->get('id'));

        $form = $this->createForm($this->namespaceType, $resource);
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($resource);
            $em->flush();

            return $resource;
        }
        return $form;
    }

    protected function patch(Request $request)
    {
        return $this->update($request, false);
    }

    /**
     * @param $id
     */
    protected function delete($id)
    {
        $resource = $this->findOneBy($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($resource);
        $em->flush();
    }

    /**
     * @param ParamFetcher $paramFetcher
     * @return mixed
     */
    protected function createQB(ParamFetcher $paramFetcher)
    {
        // On récupère le repository ou nos fonctions sql sont rangées
        $repository = $this->getDoctrine()->getRepository($this->entity);
        // On récupère la QueryBuilder instancié dans la fonctions
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        // Si un mot clef est passé en parametre on le recherche
        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);

        // On renvoie une QueryBuilder prete à etre éxécuter.
        return $qb;
    }
}
