<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($name, $email): User
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);

        $this->userRepository->add($user, true);

        return $user;
    }

    /** @return User[] */
    public function readAll(): array
    {
        $users = $this->userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = $user->getDataResponse();
        }

        return $data;
    }

    public function read($id): User
    {
        return $this->userRepository->find($id);
    }

    public function update($id, $name, $email): User
    {
        $user = $this->userRepository->find($id);
        $user->setName($name);
        $user->setEmail($email);

        $this->userRepository->update($user, true);

        return $user;
    }

    public function delete(int $id): User
    {
        $user = $this->userRepository->find($id);

        $this->userRepository->remove($user, true);

        return $user;
    }
}