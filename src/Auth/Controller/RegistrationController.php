<?php
// src/Auth/Controller/RegistrationController.php

namespace App\Auth\Controller;

use App\Auth\Security\JsonUser;
use App\UserManagement\Service\UserStorageService;
use App\Auth\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    private UserStorageService $userStorage;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserStorageService $userStorage, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userStorage = $userStorage;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/auth/register', name: 'auth_register')]
    public function register(Request $request): Response
    {
        // Using array as form data because RegistrationFormType uses data_class = null
        $formData = [
            'email' => '',
            'name' => '',
            'plainPassword' => '',
            'confirmPassword' => '',
            'agreeTerms' => false,
            'roles' => ['ROLE_USER'], // default
        ];

        $form = $this->createForm(RegistrationFormType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get submitted data as array
            $data = $form->getData();

            $plainPassword = $form->get('plainPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            // Check if passwords match
            if ($plainPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new FormError('Passwords do not match'));
            } else {
                // Create user object for password hashing
                $user = new JsonUser([
                    'email' => $data['email'],
                    'password' => '', // placeholder, will set hashed password next
                    'roles' => ['ROLE_USER'],
                ]);

                // Hash the plain password
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

                // Prepare user data for JSON storage
                $userData = [
                    'email' => $data['email'],
                    'name' => $data['name'] ?? '',
                    'password' => $hashedPassword,
                    'roles' => ['ROLE_USER'],
                ];

                // Save the user data in your JSON storage service
                $this->userStorage->addUser(
                    uniqid(), // Generate an id, you can customize this
                    $userData['name'],
                    $userData['email'],
                    $userData['password'],
                    $userData['roles']
                );

                // Redirect or show success message
                return $this->redirectToRoute('auth_login');
            }
        }

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}



