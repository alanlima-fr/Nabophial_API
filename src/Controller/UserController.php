<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @SWG\Tag(name="User")
 */
class UserController extends AbstractController
{
    protected $entity = 'App\Entity\AppUser';
    protected $namespaceType = 'App\Form\AppUserType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\AppUser", groups={"all", "user"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "user"})
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
     * Return Error in case of a not found.
     */
    protected function resourceNotFound()
    {
        throw new NotFoundHttpException('Resource not found or empty');
    }

    /**
     * Retrieve one resource from the user table
     *
     * @SWG\Response(response=200, description="return the User")
     *
     * @Rest\View(serializerGroups={"user", "all"})
     * @Rest\Route(
     *   path = "/user/{id}",
     *   methods = { Request::METHOD_GET, Request::METHOD_OPTIONS }
     * )
     *
     * @param $id
     * @return \App\Entity\AppUser|object|null
     */
    public function getOneUser($id)
    {
        $user = $this->findOne($id);

        if (!$user)
            $this->resourceNotFound();

        return $user;
    }

    /**
     * Return a resource by his id.
     *
     * @param $id
     * @return \App\Entity\AppUser|object|null
     */
    protected function findOne($id)
    {
        return $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
    }

    /**
     * Create & persist a user in database
     *
     * @SWG\Response(response=201, description="return the User created")
     *
     * @Rest\View(serializerGroups={"user", "all"})
     * @Rest\Route(
     *   path = "/signup",
     *   methods = { Request::METHOD_POST, Request::METHOD_OPTIONS }
     * )
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\Form\FormInterface
     */
    public function postUserAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new $this->entity();
        $form = $this->createForm($this->namespaceType, $user);

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $roles[] = 'ROLE_USER';
            $user->setRoles($roles);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        } else
            return $form;
    }

    /**
     * Update complete the resource
     *
     * @SWG\Response(response=200, description="return the updated User")
     *
     * @Rest\View(serializerGroups={"all", "user"})
     * @Rest\Put("/user/{id}")
     *
     * @param Request $request
     * @return \App\Entity\AppUser|object|\Symfony\Component\Form\FormInterface|null
     */
    public function put(Request $request)
    {
        return $this->update($request, true);
    }

    /**
     * Update partial the resource
     *
     * @SWG\Response(response=200, description="return the updated User")
     *
     * @Rest\View(serializerGroups={"all", "user"})
     * @Rest\Patch("/user/{id}")
     *
     * @param Request $request
     * @return \App\Entity\AppUser|object|\Symfony\Component\Form\FormInterface|null
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

        if ($form->isSubmitted() && $form->isValid()) {
            $em->merge($user);
            $em->flush();

            return $user;
        } else
            return $form;
    }

    /**
     * Delete the resource
     *
     * @SWG\Response(response=204, description="return no content")
     *
     * @Rest\View(serializerGroups={"all", "user"})
     * @Rest\Delete("/user/{id}")
     *
     * @param $id
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);

        if ($user) {
            $em->remove($user);
            $em->flush();
        } else
            $this->resourceNotFound();
    }
}
