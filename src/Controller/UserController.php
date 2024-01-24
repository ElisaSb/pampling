<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepositoryInterface;
use App\Service\Request\RequestService;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property UserRepositoryInterface $userRepository
 * @property UserService $userService
 * @Route("/users")
 */
class UserController extends AbstractController
{
    public function __construct(UserRepositoryInterface $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }
    
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/new", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/user/{id}/read", name="user_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/user/{id}/remove", name="user_delete", methods={"POST"})
     */
    public function remove(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userRepository->remove($user, true);
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/user", name="api_user_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $user = $this->userService->create(
            RequestService::getField($request, 'name'),
            RequestService::getField($request, 'email')
        );

        return new JsonResponse($user->getDataResponse(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/user", name="api_users_read", methods={"GET"})
     */
    public function readAll(): JsonResponse
    {
        $users = $this->userService->readAll();

        return new JsonResponse($users, Response::HTTP_OK);
    }

    /**
     * @Route("/user/{id}", name="api_user_read", methods={"GET"})
     */
    public function read(int $id): JsonResponse
    {
        $user = $this->userService->read($id);

        return new JsonResponse($user->getDataResponse(), Response::HTTP_OK);
    }

    /**
     * @Route("/user/{id}/update", name="api_user_update", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->update(
            $id,
            RequestService::getField($request, 'name'),
            RequestService::getField($request, 'email')
        );

        return new JsonResponse($user->getDataResponse(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/user/{id}/delete", name="api_user_delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $user = $this->userService->delete($id);

        return new JsonResponse($user->getDataResponse(), Response::HTTP_OK);
    }
}
