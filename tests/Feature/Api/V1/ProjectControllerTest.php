<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthorizedUsersCannotSeeProjects()
    {
        $this->json('get', route('api.v1.projects.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $project = Project::factory()->create();

        $this->json('get', route('api.v1.projects.show', ['project' => $project]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedUsersCanSeeTheirProjects()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $this->json('get', route('api.v1.projects.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'user_id',
                        'created_at',
                        'updated_at',
                        'tasks' => [
                            'id',
                            'text',
                            'project_id',
                            'user_id',
                            'is_completed',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ]);
    }

    public function testUserCanCreateProject()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $this->json('post', route('api.v1.projects.store', Project::factory()->raw()))
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testUserCanSeeOneProject()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $project = Project::factory()->create(['user_id' => auth()->user()->id]);

        $this->json('get', route('api.v1.projects.show', ['project' => $project]))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testUserCannotSeeProjectThatBelongsToAnotherUser()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $project = Project::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->json('get', route('api.v1.projects.show', ['project' => $project]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUserCanUpdateProject()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $project = Project::factory()->create(['user_id' => auth()->user()->id]);
        $oldName = $project->name;
        $newName = 'new name';

        $this->json('put', route('api.v1.projects.update', ['project' => $project]),
            ['name' => $newName]
        )
            ->assertStatus(Response::HTTP_OK);
        $this->assertNotEquals($project->fresh()->name, $oldName);
    }

    public function testUserCanDeleteProject()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $project = Project::factory()->create(['user_id' => auth()->user()->id]);

        $this->json('delete', route('api.v1.projects.destroy', ['project' => $project]))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }
}
