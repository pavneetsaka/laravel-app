<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @test
     * */
    public function guests_cannot_manage_projects()
    {
        $project = Project::factory()->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->patch($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /**
     * @test
     * */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->get($project->path())->assertStatus(200);
    }

    /**
     * @test
     * */
    public function a_user_can_view_projects_that_they_are_invited_to()
    {
        $user = $this->signIn();

        $project = ProjectFactory::create(); // Project with owner created

        $project->invite($user);

        $this->get('/projects')
            ->assertSee($project->title);
    }

    /**
     * @test
     * */
    public function authenticated_user_cannot_view_other_projects()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /**
     * @test
     * */
    public function authenticated_user_cannot_update_other_projects()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->patch($project->path(), ['notes' => 'Changed'])->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        //When the data is received
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General notes here.'
        ];

        //From a POST request
        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        //Spit out the latest 'title' by making GET request
        $this->get($project->path())
                ->assertSee($attributes['title'])
                ->assertSee($attributes['description'])
                ->assertSee($attributes['notes']);
    }

    /**
     * @test
     * */
    public function a_user_can_update_a_project()
    {
        $attributes = ['title' => 'Changed', 'description' => 'Changed', 'notes' => 'Changed'];

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->patch($project->path(), $attributes);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /**
     * @test
     * */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /**
     * @test
     * */
    public function unauthorized_user_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($project->path())->assertStatus(403);
    }

    /**
     * @test
     * */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /**
     * @test
     * */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
