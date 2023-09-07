<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Task\CreateRequest;
use App\Http\Requests\Api\V1\Task\UpdateRequest;
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function store(CreateRequest $request, Project $project): TaskResource
    {
        $this->authorize('isOwner', $project);

        $task = $project->tasks()->create(array_merge(
            $request->validated(), ['user_id' => $request->user()->id]
        ));

        return new TaskResource($task);
    }

    public function update(UpdateRequest $request, Project $project, Task $task): TaskResource
    {
        $this->authorize('isOwner', $project);
        $task->update($request->validated());

        return new TaskResource($task->fresh());
    }

    public function destroy(Project $project, Task $task): Response
    {
        $this->authorize('isOwner', $project);
        $task->delete();

        return response()->noContent();
    }
}
