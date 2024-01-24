<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function find($id): ?User;

    /**
     * @return User[]
     */
    public function findAll(): array;
    public function add(User $entity, bool $flush = false): void;
    public function remove(User $entity, bool $flush = false): void;
}