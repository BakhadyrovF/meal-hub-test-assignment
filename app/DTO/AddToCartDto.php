<?php

namespace App\DTO;

final class AddToCartDto
{
    /** @var int */
    private $userId;

    /** @var string */
    private $productName;

    public function __construct(int $userId, string $productName)
    {
        $this->userId = $userId;
        $this->productName = $productName;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getProductName()
    {
        return $this->productName;
    }
}
