<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProjectRequest;
use App\Http\Resources\Api\V1\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $projects = request()->user()->projects;

        return ProjectResource::collection($projects->loadMissing('tasks'));
    }

    public function store(ProjectRequest $request): ProjectResource
    {
        $project = Project::create(array_merge(
            $request->validated(), ['user_id' => $request->user()->id]
        ));

        return new ProjectResource($project);
    }

    public function show(Project $project): ProjectResource
    {
        $this->authorize('isOwner', $project);

        return new ProjectResource($project->loadMissing('tasks'));
    }

    public function update(ProjectRequest $request, Project $project): ProjectResource
    {
        $this->authorize('isOwner', $project);
        $project->update($request->validated());

        return new ProjectResource($project);
    }

    public function destroy(Project $project): Response
    {
        $this->authorize('isOwner', $project);
        $project->delete();

        return response()->noContent();
    }
}
