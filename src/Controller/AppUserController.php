<?php

namespace App\Controller;

use App\Entity\AppUser;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @SWG\Tag(name="User")
 */
class AppUserController extends DefaultController
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
     *
     * @param ParamFetcher $paramFetcher
     * @return
     */
    public function getUsers(ParamFetcher $paramFetcher)
    {
        // On récupère le repository ou nos fonctions sql sont rangées
        $repository = $this->getDoctrine()->getRepository($this->entity);
        // On récupère la QueryBuilder instancié dans la fonctions
        $qb = $repository->findAllSortBy($paramFetcher->get('sortBy'), $paramFetcher->get('sortOrder'));

        // Si un mot clef est passé en parametre on le recherche
        if ($textSearch = $paramFetcher->get('textSearch'))
            $qb = $repository->prepTextSearch($qb, $textSearch);

        // On retourne la QuerybBuilder pour la pagination
        return $this->paginate($qb,
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );
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
     * @return AppUser|object|null
     */
    public function getOneUser($id)
    {
        return $this->getOne($id);
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
     * @return FormInterface | AppUser
     */
    public function postUserAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new AppUser();
        $form = $this->createForm($this->namespaceType, $user);

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

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
     * @return AppUser|object|FormInterface|null
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
     * @return AppUser|object|FormInterface|null
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
     * @Rest\View(serializerGroups={"all", "user"})
     * @Rest\Delete("/user/{id}")
     *
     * @param $id
     */
    public function delete($id)
    {
        $this->delete($id);
    }
}
