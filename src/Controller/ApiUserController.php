<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property UserService $userService
 * @Route("/user")
 */
class ApiUserController extends BaseController
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("", name="api_user_create", methods={"POST"})
     * @OA\Tag(name="api user")
     * @OA\Response(
     *     response=201,
     *     description="Returns the data of created user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"read"}))
     * )
     * @OA\RequestBody(
     *    @OA\JsonContent(ref=@Model(type=User::class, groups={"create"}))
     * )
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
     * @Route("", name="api_users_read", methods={"GET"})
     * @OA\Tag(name="api user")
     * @OA\Response(
     *     response=200,
     *     description="Returns list of users",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"read"}))
     *     )
     * )
     */
    public function readAll(): JsonResponse
    {
        $users = $this->userService->readAll();

        return new JsonResponse($users, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_user_read", methods={"GET"})
     * @OA\Tag(name="api user")
     * @OA\Response(
     *     response=200,
     *     description="Returns the data of the user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"read"}))
     * )
     */
    public function read(int $id): JsonResponse
    {
        $user = $this->userService->read($id);

        return new JsonResponse($user->getDataResponse(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/update", name="api_user_update", methods={"PUT"})
     * @OA\Tag(name="api user")
     * @OA\Response(
     *     response=200,
     *     description="Returns the data of updated user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"read"}))
     *  )
     * @OA\RequestBody(
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"update"}))
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->update(
            $id,
            RequestService::getField($request, 'name'),
            RequestService::getField($request, 'email')
        );

        return new JsonResponse($user->getDataResponse(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/delete", name="api_user_delete", methods={"DELETE"})
     * @OA\Tag(name="api user")
     * @OA\Response(
     *     response=200,
     *     description="Returns the data of deleted user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"read"}))
     * )
     */
    public function delete(int $id): JsonResponse
    {
        $user = $this->userService->delete($id);

        return new JsonResponse($user->getDataResponse(), Response::HTTP_OK);
    }
}
