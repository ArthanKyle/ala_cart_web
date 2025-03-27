<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use App\Models\Category;

class MessageSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        $category = Category::firstOrCreate(['name' => 'General']);

        Message::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'John Doe',
            'iconpath' => 'path/to/icon.png',
            'chat' => 'Hello, this is a test message!',
            'timestamp' => now(),
            'hasUnread' => true,
            'isMe' => true,
        ]);
    }
}
