<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthorizedUsersCannotManageTasks()
    {
        $project = Project::factory()->hasTasks(1)->create();

        $this->json('post',
            route('api.v1.projects.tasks.store', ['project' => $project]),
            Task::factory()->raw())
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->json('put',
            route('api.v1.projects.tasks.update',
                ['project' => $project, 'task' => $project->tasks->first()]
            ), Task::factory()->raw())
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->json('delete',
            route('api.v1.projects.tasks.destroy',
                ['project' => $project, 'task' => $project->tasks->first()]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedUserCanCreateTask()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $project = Project::factory()->create(['user_id' => auth()->user()->id]);

        $this->json('post',
            route('api.v1.projects.tasks.store', ['project' => $project]),
            Task::factory()->raw())
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testAuthorizedUserCanUpdateTask()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $project = Project::factory()->hasTasks(1)->create(['user_id' => auth()->user()->id]);

        $this->json('put',
            route('api.v1.projects.tasks.update',
                ['project' => $project, 'task' => $project->tasks->first()]
            ), Task::factory()->raw())
            ->assertStatus(Response::HTTP_OK);
    }

    public function testAuthorizedUserCanDeleteTask()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $project = Project::factory()->hasTasks(1)->create(['user_id' => auth()->user()->id]);

        $this->json('delete',
            route('api.v1.projects.tasks.destroy',
                ['project' => $project, 'task' => $project->tasks->first()]))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
