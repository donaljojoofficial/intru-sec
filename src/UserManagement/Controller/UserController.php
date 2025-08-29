<?php
// src/Controller/UserController.php
namespace App\UserManagement\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserStorageService;

class UserController extends AbstractController
{
    private UserStorageService $userStorage;

    public function __construct(UserStorageService $userStorage)
    {
        $this->userStorage = $userStorage;
    }

    #[Route('/user/add', name: 'user_add')]
    public function addUser(): Response
    {
        $added = $this->userStorage->addUser(1, 'Alice', 'alice@example.com');

        if ($added) {
            return new Response('User added successfully.');
        } else {
            return new Response('User already exists.');
        }
    }

    #[Route('/user/list', name: 'user_list')]
    public function listUsers(): Response
    {
        $users = $this->userStorage->readUsers();

        return $this->json($users);
    }
}
