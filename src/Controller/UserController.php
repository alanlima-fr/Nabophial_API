<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    protected $entity = 'App\Entity\User';
    protected $namespaceType = 'App\Form\UserType';

    /**
     * Retrieve all data from one table
     * 
     * @Rest\View()
     * @Rest\Route(
     *      name = "user_list",
     *      path = "/user",
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
    public function getUsers(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getRepository($this->entity); // On récupère le repository ou nos fonctions sql sont rangées
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder')); // On récupère la QueryBuilder instancié dans la fonctions

        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);
        
        $qb = $repository->pageLimit($qb, $paramFetcher->get('page'), $paramFetcher->get('limit'));
        
        $user = $qb->getQuery()->getResult();

        if (!$user)
            $this->resourceNotFound();

        return $user;
    }

    /**
     * @Rest\View(serializerGroups={"user", "all"})
     * @Rest\Route(
     *   path = "/user/{id}",
     *   methods = { Request::METHOD_GET, Request::METHOD_OPTIONS }
     * )
     */
    public function getOneUser($id)
    {
        $user = $this->findOne($id);

        if (!$user)
            $this->resourceNotFound();

        return $user;
    }
    
    /**
     * @Rest\View(serializerGroups={"user", "all"})
     * @Rest\Route(
     *   path = "/signup",
     *   methods = { Request::METHOD_POST, Request::METHOD_OPTIONS }
     * )
     */
    public function postUserAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new $this->entity();
        $form = $this->createForm($this->namespaceType, $user);

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid())
        {
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $roles[] = 'ROLE_USER';
            $user->setRoles($roles);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        }
        else
            return $form;
    }

    /**
     * Update complete the resource
     * 
     * @Rest\View()
     * @Rest\Put("/user/{id}")
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }    
    
    /**
     * Update partial the resource
     * 
     * @Rest\View()
     * @Rest\Patch("/user/{id}")
     */
    public function patch(Request $request)
    {
        return $this->update($request, false);
    }
    
    protected function update($request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->findOne($request->get('id'));

        if (empty($user))
            $this->resourceNotFound();  
        
        $form = $this->createForm($this->namespaceType, $user);
        $form->submit($request->request->all(), $clearMissing);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $em->merge($user);
            $em->flush();

            return $user;
        }
        else
            return $form;
    }
    
    /**
     * Delete the resource
     * 
     * @Rest\View()
     * @Rest\Delete("/user/{id}")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
        
        if($user)
        {
            $em->remove($user);
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
