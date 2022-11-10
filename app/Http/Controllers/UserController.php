<?php

namespace App\Http\Controllers;

use App\DTO\AddToCartDto;
use App\DTO\CreateUserDto;
use App\DTO\PlaySecretSantaDto;
use App\Http\Requests\AddToCartFormRequest;
use App\Http\Requests\CreateUserFormRequest;
use App\Http\Requests\PlaySecretSantaFormRequest;
use App\Http\Resources\UserCartResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SecretSantaService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    public function index()
    {
        $users = User::with(['orders.items'])
            ->orderByDesc('id')
            ->paginate(10);

        return UserResource::collection($users);
    }

    public function store(CreateUserFormRequest $request)
    {
        $dto = new CreateUserDto($request->validated('name'));
        $user = $this->service->store($dto);

        return new UserResource($user);
    }

    public function addToCart(AddToCartFormRequest $request, $id)
    {
        $dto = new AddToCartDto($id, $request->validated('product_name'));

        $cart = $this->service->addToCart($dto);

        return UserCartResource::collection($cart);
    }

    public function delete($id)
    {
        return $this->service->delete($id)
            ? response()->noContent()
            : new JsonResponse([
                'message' => trans('Something went wron')
            ], 400);
    }

    public function playSecretSanta(PlaySecretSantaFormRequest $request, SecretSantaService $secretSantaService)
    {
        $dto = new PlaySecretSantaDto($request->validated('user_ids'));

        $secretSantaService->setParticipants($dto->getUserIds())
            ->apply();

        $usersWithReceivers = User::with('receiver')
            ->whereIn('id', $dto->getUserIds())
            ->get();

        return UserResource::collection($usersWithReceivers);
    }
}
