<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function download(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $guidanceRequest = $document->guidanceRequest;
        if (!$guidanceRequest) {
            return false;
        }

        if ($user->isStudent() && $user->student) {
            return $guidanceRequest->supervision && $guidanceRequest->supervision->student_id === $user->student->id;
        }

        if ($user->isLecturer() && $user->lecturer) {
            return $guidanceRequest->lecturer_id === $user->lecturer->id;
        }

        return false;
    }
}
