<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with role-specific content.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Route to appropriate dashboard based on user role
        if ($user->isStudent()) {
            return $this->studentDashboard($user);
        } elseif ($user->isAdvisor()) {
            return $this->advisorDashboard($user);
        } elseif ($user->isCommittee()) {
            return $this->committeeDashboard($user);
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        // Fallback for unknown roles
        return view('dashboard', [
            'user' => $user,
            'message' => 'Welcome to the Student Project Repository System'
        ]);
    }

    /**
     * Student dashboard: show their projects with status indicators.
     */
    private function studentDashboard(User $user)
    {
        // Get all projects created by the student
        $projects = Project::where('created_by', $user->id)
            ->with(['category', 'advisor', 'evaluations'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Count projects by status
        $statusCounts = [
            'draft' => $projects->where('status', 'draft')->count(),
            'submitted' => $projects->where('status', 'submitted')->count(),
            'approved' => $projects->where('status', 'approved')->count(),
            'rejected' => $projects->where('status', 'rejected')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
        ];

        return view('dashboard', [
            'user' => $user,
            'role' => 'student',
            'projects' => $projects,
            'statusCounts' => $statusCounts,
            'totalProjects' => $projects->count(),
        ]);
    }

    /**
     * Advisor dashboard: show assigned projects requiring action.
     */
    private function advisorDashboard(User $user)
    {
        // Get projects assigned to this advisor
        $assignedProjects = Project::where('advisor_id', $user->id)
            ->with(['category', 'creator', 'evaluations'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Projects requiring action (submitted but not yet approved/rejected)
        $projectsRequiringAction = $assignedProjects->where('status', 'submitted');

        // Count projects by status
        $statusCounts = [
            'submitted' => $assignedProjects->where('status', 'submitted')->count(),
            'approved' => $assignedProjects->where('status', 'approved')->count(),
            'rejected' => $assignedProjects->where('status', 'rejected')->count(),
            'completed' => $assignedProjects->where('status', 'completed')->count(),
        ];

        return view('dashboard', [
            'user' => $user,
            'role' => 'advisor',
            'projects' => $assignedProjects,
            'projectsRequiringAction' => $projectsRequiringAction,
            'statusCounts' => $statusCounts,
            'totalProjects' => $assignedProjects->count(),
        ]);
    }

    /**
     * Committee dashboard: show projects available for evaluation.
     */
    private function committeeDashboard(User $user)
    {
        // Get approved/completed projects that can be evaluated
        $availableProjects = Project::whereIn('status', ['approved', 'completed'])
            ->with(['category', 'creator', 'advisor', 'evaluations'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Projects already evaluated by this committee member
        $evaluatedProjectIds = $user->evaluations()->pluck('project_id')->toArray();

        // Projects not yet evaluated by this user
        $projectsToEvaluate = $availableProjects->whereNotIn('id', $evaluatedProjectIds);

        // Projects already evaluated by this user
        $evaluatedProjects = $availableProjects->whereIn('id', $evaluatedProjectIds);

        return view('dashboard', [
            'user' => $user,
            'role' => 'committee',
            'projectsToEvaluate' => $projectsToEvaluate,
            'evaluatedProjects' => $evaluatedProjects,
            'totalAvailable' => $availableProjects->count(),
            'totalEvaluated' => $evaluatedProjects->count(),
            'totalPending' => $projectsToEvaluate->count(),
        ]);
    }

    /**
     * Admin dashboard: show system statistics and recent activities.
     */
    private function adminDashboard(User $user)
    {
        // System statistics
        $stats = [
            'totalUsers' => User::count(),
            'totalProjects' => Project::count(),
            'totalStudents' => User::where('role', 'student')->count(),
            'totalAdvisors' => User::where('role', 'advisor')->count(),
            'totalCommittee' => User::where('role', 'committee')->count(),
        ];

        // Project statistics by status
        $projectStats = [
            'draft' => Project::where('status', 'draft')->count(),
            'submitted' => Project::where('status', 'submitted')->count(),
            'approved' => Project::where('status', 'approved')->count(),
            'rejected' => Project::where('status', 'rejected')->count(),
            'completed' => Project::where('status', 'completed')->count(),
        ];

        // Recent activities (last 10)
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent projects (last 5)
        $recentProjects = Project::with(['creator', 'advisor', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'role' => 'admin',
            'stats' => $stats,
            'projectStats' => $projectStats,
            'recentActivities' => $recentActivities,
            'recentProjects' => $recentProjects,
        ]);
    }
}

