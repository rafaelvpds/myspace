<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route("/login", name: "login")]
    public function index(AuthenticationUtils $authentication_utils): Response
    {
        $erro = $authentication_utils->getLastAuthenticationError();

        $lastEmail = $authentication_utils->getLastUsername();

        return $this->render('login/index.html.twig', [
            "controller_name" => 'LoginController',
            'erro' => $erro,
            'lastEmail' => $lastEmail
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \Exception('Este método é interceptado pelo firewall de segurança.');
    }
}
