<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectInvitationRequest;

class ProjectInvitationsController extends Controller
{
    public function store(Project $project, ProjectInvitationRequest $request)
    {
        /*$this->authorize('owner', $project);

        $attributes = request()->validate([
            'email' => 'required|email|exists:users,email'
        ]);*/

        $user = User::where('email', $request['email'])->first();

        $project->invite($user);

        return redirect($project->path())->with('success', 'Invitation sent successfully');
    }
}
