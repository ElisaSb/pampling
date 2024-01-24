<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class UserRepositoryAdapter implements UserRepositoryInterface
{

    private EntityRepository $userRepository;
    private EntityManager $entityManager;

    /**
     * @throws NotSupported
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    public function find($id): ?User
    {
        if (null === $user = $this->userRepository->find($id)) {
            throw UserNotFoundException::fromId($id);
        }

        return $user;
    }

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function add(User $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(User $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(User $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}