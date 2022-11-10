<?php

namespace App\Services;

use App\DTO\AddToCartDto;
use App\DTO\CreateUserDto;
use App\Exceptions\BusinessException;
use App\Models\User;

final class UserService
{
    /**
     * @param CreateUserDto $request
     * @return User
     */
    public function store(CreateUserDto $request)
    {
        $user = new User();
        $user->name = $request->getName();
        $user->save();

        return $user;
    }

    /**
     * @param AddToCartDto $request
     * @return \App\Models\UserCart|\Illuminate\Support\HigherOrderCollectionProxy|mixed
     * @throws BusinessException
     */
    public function addToCart(AddToCartDto $request)
    {
        $user = User::find($request->getUserId());

        if (is_null($user)) {
            throw new BusinessException(trans('User with provided ID not found'), 404);
        }

        $user->cart()->create([
            'product_name' => $request->getProductName()
        ]);
        $user->load('cart');

        return $user->cart;
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            throw new BusinessException(trans('User with provided ID not found'), 404);
        }

        return $user->delete();
    }
}
