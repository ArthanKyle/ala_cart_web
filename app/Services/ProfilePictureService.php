<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;

class ProfilePictureService implements ProfilePictureServiceInterface
{
    public function uploadProfilePicture(User $user, UploadedFile $file): string
    {
        // Remove old profile picture
        $user->clearMediaCollection('profile_pictures');

        // Add new profile picture
        $user->addMedia($file)->toMediaCollection('profile_pictures');

        return $user->getFirstMediaUrl('profile_pictures');
    }
}

