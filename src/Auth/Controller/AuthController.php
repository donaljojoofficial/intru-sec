<?php

namespace App\Auth\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth/login", name="auth_login", methods={"GET", "POST"})
     */
    public function login(Request $request): Response
    {
        // Render your login template here
        return $this->render('auth/login.html.twig');
    }

    /**
     * @Route("/auth/register", name="auth_register", methods={"GET", "POST"})
     */
    public function register(Request $request): Response
    {
        // Render your registration template here
        return $this->render('auth/register.html.twig');
    }
}
