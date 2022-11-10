<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $users = User::factory()->count(5)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data', 'links', 'meta'
            ])
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('data', 5)
                    ->where('data.4.id', $users->first()->id)
                    ->etc()
            );
    }

    public function testStore()
    {
        $requestBody = [
            'name' => 'Firuzbek'
        ];

        $response = $this->postJson(route('users.store'), $requestBody);

        $response->assertCreated()
            ->assertJsonStructure(['data'])
            ->assertJson(fn(AssertableJson $json) =>
                $json->where('data.name', $requestBody['name'])
                    ->etc()
            );
    }

    public function testAddToCart()
    {
        $user = $this->postJson(route('users.store'), [
            'name' => 'Firuzbek'
        ])->json('data');

        $productName = fake()->word();
        $response = $this->postJson(route('users.to-cart', $user['id']), [
            'product_name' => $productName
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('data', 1)
                    ->where('data.0.product_name', $productName)
                    ->etc()
            );

    }

    public function testDelete()
    {
        $user = $this->postJson(route('users.store'), [
            'name' => 'Firuzbek'
        ])->json('data');

        $cart = $this->postJson(route('users.to-cart', $user['id']), [
            'product_name' => 'Coca Cola'
        ])->json('data');

        $this->assertDatabaseHas(UserCart::class, $cart[0]);

        $order = $this->postJson(route('users.orders.store', $user['id']))->json('data');

        $this->assertDatabaseHas(User::class, ['id' => $user['id']]);
        $this->assertDatabaseHas(Order::class, ['id' => $order['id']]);
        $this->assertDatabaseHas(OrderItem::class, ['id' => $order['items'][0]['id']]);

        $response = $this->deleteJson(route('users.delete', $user['id']));

        $response->assertNoContent();

        $this->assertDatabaseMissing(User::class, ['id' => $user['id']]);
        $this->assertDatabaseMissing(UserCart::class, $cart[0]);
        $this->assertDatabaseMissing(Order::class, ['id' => $order['id']]);
        $this->assertDatabaseMissing(OrderItem::class, ['id' => $order['items'][0]['id']]);
    }
}
