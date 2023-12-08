<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * */
    public function non_owners_cannot_invite_users()
    {
        $project = ProjectFactory::create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post($project->path(). '/invitation')
            ->assertStatus(403);
    }

    /**
     * @test
     * */
    public function a_project_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $user = User::factory()->create();

        $this->actingAs($project->owner)->post($project->path().'/invitation', [
            'email' => $user->email
        ]);

        $this->assertTrue($project->members->contains($user));
    }

    /**
     * @test
     * */
    public function the_invited_user_must_have_an_associated_account_with_us()
    {
        $project = ProjectFactory::create();
        $user = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitation', [
                'email' => 'notauser@gmail.com'
            ])
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * */
    public function invited_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = User::factory()->create());

        $this->actingAs($newUser)
            ->post($project->path().'/tasks', $task = ['body' => 'Added task by new user']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
