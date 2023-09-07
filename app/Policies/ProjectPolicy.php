<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function isOwner(User $user, Project $project)
    {
        return $user->is($project->user);
    }
}
