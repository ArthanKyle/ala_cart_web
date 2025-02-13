<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Models\User;

interface ProfilePictureServiceInterface
{
    public function uploadProfilePicture(User $user, UploadedFile $file): string;
}
