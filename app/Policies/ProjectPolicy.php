<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone (including guests) can view the project list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Project $project): bool
    {
        // Published projects (approved/completed) can be viewed by anyone
        if (in_array($project->status, ['approved', 'completed'])) {
            return true;
        }

        // Non-published projects require authentication
        if (!$user) {
            return false;
        }

        // Project creator can view their own projects
        if ($user->id === $project->created_by) {
            return true;
        }

        // Assigned advisor can view the project
        if ($user->id === $project->advisor_id) {
            return true;
        }

        // Admin can view any project
        if ($user->isAdmin()) {
            return true;
        }

        // Committee members can view submitted/approved projects
        if ($user->isCommittee() && in_array($project->status, ['submitted', 'approved', 'completed'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Students can create projects
        // Advisors and admins can also create projects
        return in_array($user->role, ['student', 'advisor', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Student can update only their own draft projects
        if ($user->id === $project->created_by && $project->status === 'draft') {
            return true;
        }

        // Advisor can update projects they advise
        if ($user->id === $project->advisor_id && $user->isAdvisor()) {
            return true;
        }

        // Admin can update any project
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Only draft projects can be deleted by owner
        if ($user->id === $project->created_by && $project->status === 'draft') {
            return true;
        }

        // Admin can delete any project
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can submit the project.
     */
    public function submit(User $user, Project $project): bool
    {
        // Only the project creator can submit
        // Project must be in draft status
        return $user->id === $project->created_by && $project->status === 'draft';
    }

    /**
     * Determine whether the user can approve the project.
     */
    public function approve(User $user, Project $project): bool
    {
        // Only assigned advisor can approve
        // Project must be in submitted status
        return $user->id === $project->advisor_id 
            && $user->isAdvisor() 
            && $project->status === 'submitted';
    }

    /**
     * Determine whether the user can reject the project.
     */
    public function reject(User $user, Project $project): bool
    {
        // Only assigned advisor can reject
        // Project must be in submitted status
        return $user->id === $project->advisor_id 
            && $user->isAdvisor() 
            && $project->status === 'submitted';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // Only admin can restore deleted projects
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // Only admin can permanently delete projects
        return $user->isAdmin();
    }
}
