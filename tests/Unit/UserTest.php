<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test */
    public function a_user_has_many_projects()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /**
     * @test
     * */
    public function a_user_can_view_accesible_projects()
    {
        $pavneet = $this->signIn();
        $project = ProjectFactory::ownedBy($pavneet)->create();

        $this->assertCount(1, $pavneet->accessibleProjects());

        $sakshee = User::factory()->create();
        $vipul = User::factory()->create();
        $saksheeProject = tap(ProjectFactory::ownedBy($sakshee)->create())->invite($vipul);

        $this->assertCount(1, $pavneet->accessibleProjects());

        $saksheeProject->invite($pavneet);

        $this->assertCount(2, $pavneet->accessibleProjects());
    }
}
