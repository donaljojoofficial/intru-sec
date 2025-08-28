<?php
namespace App\Security;

use App\Service\UserStorageService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class JsonUserProvider implements UserProviderInterface
{
    private UserStorageService $userStorage;

    public function __construct(UserStorageService $userStorage)
    {
        $this->userStorage = $userStorage;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userStorage->findUserByEmail($identifier);
        if (!$user) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
        }
        return new JsonUser($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return JsonUser::class === $class;
    }
}
