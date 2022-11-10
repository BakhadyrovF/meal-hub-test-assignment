<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {

        $user = $this->postJson(route('users.store'), [
            'name' => 'Firuzbek'
        ])->json('data');

        $productName = 'Coca Cola';
        $this->postJson(route('users.to-cart', $user['id']), [
            'product_name' => $productName
        ]);

        $secondProductName = 'Fanta';
        $this->postJson(route('users.to-cart', $user['id']), [
            'product_name' => $secondProductName
        ]);

        $response = $this->postJson(route('users.orders.store', $user['id']));

        $response->assertCreated()
            ->assertJsonStructure(['data'])
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('data.items', 2)
                    ->where('data.items.0.name', $productName)
                    ->where('data.items.1.name', $secondProductName)
                    ->etc()
            );


    }
}
