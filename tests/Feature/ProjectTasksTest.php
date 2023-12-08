<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @test
     * */
    public function guest_cannot_add_task_to_a_project()
    {
        $project = Project::factory()->create();

        $this->post($project->path().'/tasks')->assertRedirect('login');
    }

    /**
     * @test
     * */
    public function only_owner_of_a_project_may_add_task()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->post($project->path().'/tasks', ['body' => 'task body'])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'task body']);
    }

    /**
     * @test
     * */
    public function only_owner_of_a_project_may_update_task()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'task body'])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'task body']);
    }

    /**
     * @test
     * */
    public function a_project_can_have_tasks()
    {
        //Turn off graceful exception handling
        // $this->withoutExceptionhandling();

        //Create and signin a user, post that create a Project with ower as recently created user
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        //POST request to /project/11/tasks with request
        $this->post($project->path().'/tasks', ['body' => 'Test Task']);

        //GET request to project inner page
        $this->get($project->path())
            ->assertSee('Test Task');
    }

    /**
     * @test
     * */
    public function a_task_can_be_updated()
    {
        // $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        /*$this->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ]);*/

        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(), [
            'body' => 'changed'
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed'
        ]);
    }

    /**
     * @test
     * */
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /**
     * @test
     * */
    public function a_task_can_be_marked_as_incomplete()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->patch($project->tasks->first()->path(), [
            'body' => 'updated',
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks', [
            'completed' => false
        ]);
    }

    /**
     * @test
     * */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        $attributes = Task::factory()->raw(['body' => '']);

        $this->actingAs($project->owner)->post($project->path().'/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}
