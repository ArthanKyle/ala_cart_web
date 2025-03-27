<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Lunar\Base\Traits\LunarUser;
use Lunar\Models\Customer;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, InteractsWithMedia, LunarUser, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $appends = ['full_name', 'profile_picture_url'];

    protected $fillable = [
        'name', //Shop Messages
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'address',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Shop Messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        return trim($this->first_name.' '.($this->middle_name ?? '').' '.$this->last_name);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function getProfilePictureUrlAttribute()
    {
        $media = $this->getFirstMedia('profile_pictures');

        return $media ? $media->getUrl() : asset('public\storage\profile_pictures\default_profile.jpg');
    }
}
