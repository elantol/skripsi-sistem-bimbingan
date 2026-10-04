<?php

namespace App\Policies;

use App\Models\GuidanceRequest;
use App\Models\User;

class GuidanceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, GuidanceRequest $guidanceRequest): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent() && $user->student) {
            return $guidanceRequest->supervision && $guidanceRequest->supervision->student_id === $user->student->id;
        }

        if ($user->isLecturer() && $user->lecturer) {
            return $guidanceRequest->lecturer_id === $user->lecturer->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isStudent() && $user->student !== null;
    }

    public function review(User $user, GuidanceRequest $guidanceRequest): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isLecturer() && $user->lecturer && $guidanceRequest->lecturer_id === $user->lecturer->id;
    }
}
