<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserCart;
use App\Services\SecretSantaService;
use Exception;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run(SecretSantaService $secretSantaService)
    {
        $users = User::factory()->count(100)
            ->has(
                Order::factory()->count(10)
                    ->has(
                        OrderItem::factory()->count(5), 'items'
                    )
            )->has(
                UserCart::factory()->count(5), 'cart'
            )->create();

        $secretSantaService->setParticipants($users)
            ->apply();

    }
}
