<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * */
    public function it_knows_its_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals('/projects/'.$project->id, $project->path());
    }

    /**
     * @test
     * */
    public function it_belongs_to_a_owner()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf('\App\Models\User', $project->owner);
    }

    /**
     * @test
     * */
    public function it_can_add_a_task()
    {
        $project = Project::factory()->create();

        $task = $project->addTask('Test Body');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /**
     * @test
     * */
    public function it_can_invite_a_user()
    {
        $project = Project::factory()->create();

        $project->invite($newUser = User::factory()->create());

        $this->assertTrue($project->members->contains($newUser));
    }
}
