<?php

namespace App\Services;

use App\DTO\CreateOrderDto;
use App\Exceptions\BusinessException;
use App\Models\Order;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;

final class OrderService
{
    /**
     * @param CreateOrderDto $request
     * @return Order|\Illuminate\Database\Eloquent\Model
     * @throws BusinessException
     */
    public function store(CreateOrderDto $request)
    {
        $user = User::with('cart')
            ->find($request->getUserId());

        if (is_null($user)) {
            throw new BusinessException(trans('User with provided ID not found'), 404);
        }

        if ($user->cart->isEmpty()) {
            throw new BusinessException(trans('User`s cart is empty'));
        }

        DB::beginTransaction();
        try {
            $order = $user->orders()->create();
            $order->items()->createMany($user->cart->reduce(function ($carry, $item) {
                $carry[] = ['name' => $item->product_name];

                return $carry;
            }, []));

            $user->cart()->delete();
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error($exception->getMessage());
        }
        DB::commit();

        $order->load('items');
        return $order;
    }
}
