<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->checkIfAuthenticated()) {
            return $this->redirectToRoute('index');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function loginAjax(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        if ($this->checkIfAuthenticated()) {
            return new JsonResponse([
                'login_success' => true
            ]);
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->json([
            'login_success' => false,
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    private function checkIfAuthenticated()
    {
        return $this->isGranted([
            'IS_AUTHENTICATED_FULLY',
            'IS_AUTHENTICATED_REMEMBERED'
        ]);
    }
}
