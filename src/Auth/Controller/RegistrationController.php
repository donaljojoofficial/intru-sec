<?php
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
        // Initialize default form data
        $formData = [
            'email' => '',
            'name' => '',
            'plainPassword' => '',
            'confirmPassword' => '',
            'agreeTerms' => false,
            'roles' => ['ROLE_USER'], // default role
        ];

        // Create form with initial data
        $form = $this->createForm(RegistrationFormType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $plainPassword = $form->get('plainPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            // Validate password confirmation
            if ($plainPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new FormError('Passwords do not match.'));
            } else {
                // Create a new user for password hashing
                $user = new JsonUser([
                    'email' => $data['email'],
                    'password' => '',
                    'roles' => ['ROLE_USER'],
                ]);

                // Hash the password
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

                // Save user with hashed password using your storage service
                $this->userStorage->addUser(
                    uniqid(),
                    $data['name'] ?? '',
                    $data['email'],
                    $hashedPassword,
                    ['ROLE_USER']
                );

                // Redirect to login after successful registration
                return $this->redirectToRoute('auth_login');
            }
        }

        // Pass the form view as 'registrationForm' to Twig template
        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

