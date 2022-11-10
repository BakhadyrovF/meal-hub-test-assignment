<?php

namespace App\Http\Controllers;

use App\DTO\CreateOrderDto;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function store($id)
    {
        $dto = new CreateOrderDto($id);
        $order = $this->service->store($dto);


        return new OrderResource($order);
    }

}
