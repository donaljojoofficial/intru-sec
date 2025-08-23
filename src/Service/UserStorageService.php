<?php
// src/Service/UserStorageService.php
namespace App\Service;

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
        file_put_contents($this->userFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function addUser(int $id, string $name, string $email): bool
    {
        $users = $this->readUsers();

        foreach ($users as $user) {
            if ($user['id'] === $id) {
                return false; // User exists
            }
        }

        $users[] = ['id' => $id, 'name' => $name, 'email' => $email];
        $this->saveUsers($users);
        return true;
    }
}
