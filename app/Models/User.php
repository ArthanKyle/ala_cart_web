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
    use HasApiTokens, HasFactory, LunarUser, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $appends = ['full_name', 'profile_picture_url'];

    protected $fillable = [
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

        return $media ? $media->getUrl() : asset('storage/profile_pictures/default_profile.jpg');
    }
}
