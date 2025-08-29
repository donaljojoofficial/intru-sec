<?php
namespace App\Auth\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class JsonUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $email;
    private string $password;
    private array $roles;

    public function __construct(array $data)
    {
        $this->email = $data['email'];
        $this->password = $data['password'] ?? ''; // Set password or empty if missing
        $this->roles = $data['roles'] ?? ['ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Returns the hashed password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary sensitive information on the user, clear it here
    }
}


