<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use App\Entity\AuthToken;
use App\Entity\Credentials;

class AuthTokenController extends AbstractController
{
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"auth-token"})
     * @Rest\Route(
     *   path = "/signin",
     *   name = "signin",
     *   methods = { Request::METHOD_POST, Request::METHOD_OPTIONS }
     * )
     */
    public function postAuthTokenAction(Request $request)
    {
        $credentials = new Credentials();
        $form = $this->createForm("App\Form\CredentialsType", $credentials);

        $form->submit($request->request->all());

        if (!$form->isValid())
            return $form;
        
        $em = $this->get('doctrine.orm.entity_manager');

        $user = $em->getRepository('App:User')
            ->findOneByEmail($credentials->getLogin());

        if (!$user)
            return $this->invalidCredentials();
        
        $encoder = $this->get('security.password_encoder');
        $isPasswordValid = $encoder->isPasswordValid($user, $credentials->getPassword());

        if (!$isPasswordValid)
            return $this->invalidCredentials();

        $authToken = new AuthToken();
        $authToken->setValue(base64_encode(random_bytes(50)));
        $authToken->setCreatedAt(new \DateTime('now'));
        $authToken->setUser($user);

        $em->persist($authToken);
        $em->flush();

        return $authToken;
    }

    private function invalidCredentials()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Route(
     *   path = "/logout/{id}",
     *   name = "logout",
     *   methods = { Request::METHOD_DELETE, Request::METHOD_OPTIONS }
     * )
     */
    public function removeAuthTokenAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $authToken = $em->getRepository('App:AuthToken')
                    ->find($request->get('id'));

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($authToken && $authToken->getUser()->getId() === $connectedUser->getId()) 
        {
            $em->remove($authToken);
            $em->flush();
        }
        else
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException();
    }
}
