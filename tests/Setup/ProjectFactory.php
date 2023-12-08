<?php

namespace Tests\Setup;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;

class ProjectFactory
{
    protected $tasksCount = 0;
    protected $user;

    public function withTasks($tasks)
    {
        $this->tasksCount = $tasks;

        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::factory()->create()->id
        ]);

        Task::factory($this->tasksCount)->create([
            'project_id' => $project->id
        ]);

        return $project;
    }
}

?>