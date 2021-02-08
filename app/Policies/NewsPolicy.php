<?php

namespace App\Policies;

use App\Models\User;
use App\Models\News;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, News $news)
    {
        return $user->id === $news->created_by;
    }
}
