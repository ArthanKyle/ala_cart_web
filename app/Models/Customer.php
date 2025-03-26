<?php

namespace App\Models;

use Lunar\Models\Customer as LunarCustomer;

class Customer extends LunarCustomer
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
