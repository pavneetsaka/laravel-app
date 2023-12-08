<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        $attributes = request()->validate([
            'body' => 'required'
        ]);

        $project->addTask($attributes['body']);

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $task->update(request()->validate([
            'body' => 'required'
        ]));

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->path());
    }
}
