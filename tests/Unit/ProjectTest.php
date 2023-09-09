<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function testBelongsToUser(): void
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->user);
    }

    public function testHasManyTasks(): void
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(Collection::class, $project->tasks);
    }
}
