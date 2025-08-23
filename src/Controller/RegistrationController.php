<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Service\UserStorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private UserStorageService $userStorage;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserStorageService $userStorage, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userStorage = $userStorage;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        // Use an array for user data instead of entity
        $user = [
            'id' => null,
            'email' => '',
            'username' => '',
            'plainPassword' => '',
            'password' => '',
            'roles' => ['ROLE_USER']
        ];

        $form = $this->createForm(\App\Form\RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Generate unique id for user (e.g., timestamp)
            $data['id'] = time();

            // Hash password
            $data['password'] = $this->passwordHasher->hashPassword(
                null,
                $data['plainPassword']
            );

            // Remove plainPassword before saving
            unset($data['plainPassword']);

            // Save user to JSON storage
            $this->userStorage->addUser(
                $data['id'],
                $data['username'],
                $data['email'],
                $data['password'],
                $data['roles']
            );

            // Redirect or display success message
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
