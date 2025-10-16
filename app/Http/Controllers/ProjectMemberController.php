<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectMemberController extends Controller
{
    /**
     * Store a newly created team member.
     */
    public function store(Request $request, Project $project)
    {
        // Check authorization - only project creator or admin can add members
        if (!$project->canBeEditedBy(auth()->user())) {
            abort(403, 'Unauthorized to add team members to this project.');
        }

        // Validate input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_in_team' => 'required|string|in:leader,member',
        ]);

        // Check for duplicate members
        $existingMember = ProjectMember::where('project_id', $project->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existingMember) {
            return redirect()->back()
                ->withErrors(['user_id' => 'This user is already a member of the project.'])
                ->withInput();
        }

        // Save team member association
        $projectMember = ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $validated['user_id'],
            'role_in_team' => $validated['role_in_team'],
        ]);

        // Log activity
        $memberUser = User::find($validated['user_id']);
        ActivityLogger::log('added_team_member', "Added {$memberUser->name} as {$validated['role_in_team']} to project '{$project->title_en}' (ID: {$project->id})");

        return redirect()->back()
            ->with('success', 'Team member added successfully.');
    }

    /**
     * Remove the specified team member.
     */
    public function destroy(Project $project, ProjectMember $member)
    {
        // Verify the member belongs to this project
        if ($member->project_id !== $project->id) {
            abort(404);
        }

        // Check authorization - only project creator or admin can remove members
        $user = auth()->user();
        if ($user->id !== $project->created_by && !$user->isAdmin()) {
            abort(403, 'Unauthorized to remove team members from this project.');
        }

        // Get member info before deleting
        $memberUser = $member->user;
        $memberName = $memberUser->name;

        // Delete team member association
        $member->delete();

        // Log activity
        ActivityLogger::log('removed_team_member', "Removed {$memberName} from project '{$project->title_en}' (ID: {$project->id})");

        return redirect()->back()
            ->with('success', 'Team member removed successfully.');
    }
}
