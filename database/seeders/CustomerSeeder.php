<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Lunar\Models\Address;
use Lunar\Models\Customer;

class CustomerSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $users = User::all();

            if ($users->isEmpty()) {
                throw new \Exception("No users found! Run UserSeeder first.");
            }

            foreach ($users as $user) {
                $customer = Customer::factory()->create([
                    'first_name'  => $user->first_name,
                    'last_name'   => $user->last_name,
                ]);

                $customer->users()->attach($user);

                Address::factory()->create([
                    'shipping_default' => true,
                    'country_id' => 235,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'country_id' => 235,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'billing_default' => true,
                    'country_id' => 235,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'billing_default' => false,
                    'country_id' => 235,
                    'customer_id' => $customer->id,
                ]);
            }
        });
    }
}
