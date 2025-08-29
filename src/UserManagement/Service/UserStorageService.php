<?php
// src/Service/UserStorageService.php
namespace App\UserManagement\Service;

class UserStorageService
{
    private string $userFile;

    public function __construct(string $projectDir)
    {
        $this->userFile = $projectDir . '/data/users.json';
    }

    public function readUsers(): array
    {
        if (!file_exists($this->userFile)) {
            return [];
        }
        $json = file_get_contents($this->userFile);
        return json_decode($json, true) ?: [];
    }

    public function saveUsers(array $users): void
    {
        try {
            $json = json_encode($users, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
            if ($json === false) {
                throw new \RuntimeException('Failed to encode users data to JSON.');
            }

            $result = file_put_contents($this->userFile, $json);
            if ($result === false) {
                throw new \RuntimeException('Failed to write users data to file.');
            }
        } catch (\Throwable $e) {
            // Log or rethrow exception
            throw new \RuntimeException('Error saving users data: ' . $e->getMessage());
        }
    }

    public function addUser(string $id, string $name, string $email, string $hashedPassword, array $roles = ['ROLE_USER']): bool
    {
        $users = $this->readUsers();

        // Check if a user with the same ID or email already exists
        foreach ($users as $user) {
            if ($user['id'] === $id || $user['email'] === $email) {
                return false; // User already exists
            }
        }

        $users[] = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'roles' => $roles,
        ];

        $this->saveUsers($users);
        return true;
    }
}


