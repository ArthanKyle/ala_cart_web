<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lunar\Models\Customer;

class AuthService
{
    public function registerUser(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'phone_number' => $data['phone_number'] ?? null,
                'address' => $data['address'] ?? null,
                'profile_picture' => $data['profile_picture'] ?? null,
            ]);

            Customer::create([
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);

            return $user;
        });
    }

    public function authenticateUser($email, $password)
    {
        $user = User::where('email', $email)->first();
        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }
}
