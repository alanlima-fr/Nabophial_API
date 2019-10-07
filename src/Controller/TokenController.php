<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Date;

class TokenController extends AbstractController
{
    /**
     * @SWG\Tag(name="Login")
     * @SWG\Response(response=201, description="return the token created")
     *
     * @Rest\View()
     * @Rest\Route(
     *     name = "login",
     *     path = "/login",
     *     methods = {Request::METHOD_POST})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTEncoderInterface $JWTEncoder
     * @return string
     * @throws JWTEncodeFailureException
     */
    public function newTokenAction(Request $request, UserPasswordEncoderInterface $encoder, JWTEncoderInterface $JWTEncoder)
    {
        $user = $this->getDoctrine()
            ->getRepository('App:AppUser')
            ->findOneBy(['email' => $request->get('email')]);

        if (!$user || empty($user)) {
            throw new BadCredentialsException("Bad credentials");
        }

        if (!$isValid = $encoder->isPasswordValid($user, $request->get('plainPassword'))) {
            throw  new BadCredentialsException("Bad credentials");
        }

        $token = $JWTEncoder->encode([
            'email' => $user->getUsername(),
            'exp' => time() + 86400, // 24 hours expiration
            'roles' => $user->getRoles(),
            'id' => $user->getId()
        ]);
        $em = $this->getDoctrine()->getManager();

        return new JsonResponse(['token' => $token]);
    }
}