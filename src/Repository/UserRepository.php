<?php

declare(strict_types=1);

namespace ExercisePromo\Repository;

use Doctrine\DBAL\Connection;
use ExercisePromo\Entity\User;

class UserRepository
{
    public function __construct(
        private Connection $dbal
    ) {}

    public function find(int $id): ?User
    {
        $result = $this->dbal->fetchAssociative('SELECT * FROM users WHERE id = :id', ['id' => $id]);

        if (!$result) {
            return null;
        }

        return User::fromArray($result);
    }

    public function findByUsername(string $username): ?User
    {
        $result = $this->dbal->fetchAssociative('SELECT * FROM users WHERE username = :username', ['username' => $username]);

        if (!$result) {
            return null;
        }

        return User::fromArray($result);
    }

    public function create(User $user): bool
    {
        $result = $this->dbal->insert('users', array_filter($user->asArray()));

        return ((int) $result) > 0;
    }
}
