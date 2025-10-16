<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Category;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports page with various report options.
     * Requirements: 13.1, 13.5
     */
    public function index(Request $request)
    {
        // Check authorization (admin, advisor, or committee)
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAdvisor() && !$user->isCommittee()) {
            abort(403, 'Unauthorized access to reports.');
        }

        // Get summary data for the reports page
        $totalProjects = Project::count();
        $totalEvaluations = Evaluation::count();
        $totalCategories = Category::count();
        $totalAdvisors = User::where('role', 'advisor')->count();

        // Projects by year
        $projectsByYear = Project::select('year', DB::raw('count(*) as count'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Projects by category
        $projectsByCategory = Category::withCount('projects')
            ->orderBy('projects_count', 'desc')
            ->get();

        // Average scores by category
        $averageScoresByCategory = Category::select('categories.id', 'categories.name')
            ->leftJoin('projects', 'categories.id', '=', 'projects.category_id')
            ->leftJoin('evaluations', 'projects.id', '=', 'evaluations.project_id')
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('
                AVG(evaluations.technical_score) as avg_technical,
                AVG(evaluations.design_score) as avg_design,
                AVG(evaluations.documentation_score) as avg_documentation,
                AVG(evaluations.presentation_score) as avg_presentation,
                AVG(evaluations.total_score) as avg_total,
                COUNT(DISTINCT evaluations.id) as evaluation_count
            ')
            ->get();

        // Projects per advisor
        $advisorProjects = User::where('role', 'advisor')
            ->withCount('advisedProjects')
            ->orderBy('advised_projects_count', 'desc')
            ->get();

        return view('reports.index', [
            'totalProjects' => $totalProjects,
            'totalEvaluations' => $totalEvaluations,
            'totalCategories' => $totalCategories,
            'totalAdvisors' => $totalAdvisors,
            'projectsByYear' => $projectsByYear,
            'projectsByCategory' => $projectsByCategory,
            'averageScoresByCategory' => $averageScoresByCategory,
            'advisorProjects' => $advisorProjects,
        ]);
    }

    /**
     * Query and group projects by year.
     * Requirements: 13.1
     */
    public function projectsByYear(Request $request)
    {
        // Check authorization
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAdvisor() && !$user->isCommittee()) {
            abort(403, 'Unauthorized access to reports.');
        }

        // Group projects by year and count
        $projectsByYear = Project::select('year', DB::raw('count(*) as count'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Calculate total
        $total = $projectsByYear->sum('count');

        return view('reports.projects-by-year', [
            'projectsByYear' => $projectsByYear,
            'total' => $total,
        ]);
    }

    /**
     * Query and group projects by category.
     * Requirements: 13.1
     */
    public function projectsByCategory(Request $request)
    {
        // Check authorization
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAdvisor() && !$user->isCommittee()) {
            abort(403, 'Unauthorized access to reports.');
        }

        // Group projects by category and count
        $projectsByCategory = Category::withCount('projects')
            ->orderBy('projects_count', 'desc')
            ->get();

        // Calculate total
        $total = $projectsByCategory->sum('projects_count');

        return view('reports.projects-by-category', [
            'projectsByCategory' => $projectsByCategory,
            'total' => $total,
        ]);
    }

    /**
     * Calculate average evaluation scores across all projects.
     * Display by category or year.
     * Requirements: 13.2
     */
    public function averageScores(Request $request)
    {
        // Check authorization
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAdvisor() && !$user->isCommittee()) {
            abort(403, 'Unauthorized access to reports.');
        }

        $groupBy = $request->input('group_by', 'overall'); // overall, category, or year

        if ($groupBy === 'category') {
            // Average scores by category
            $averageScores = Category::select('categories.id', 'categories.name')
                ->leftJoin('projects', 'categories.id', '=', 'projects.category_id')
                ->leftJoin('evaluations', 'projects.id', '=', 'evaluations.project_id')
                ->groupBy('categories.id', 'categories.name')
                ->selectRaw('
                    AVG(evaluations.technical_score) as avg_technical,
                    AVG(evaluations.design_score) as avg_design,
                    AVG(evaluations.documentation_score) as avg_documentation,
                    AVG(evaluations.presentation_score) as avg_presentation,
                    AVG(evaluations.total_score) as avg_total,
                    COUNT(DISTINCT evaluations.id) as evaluation_count
                ')
                ->get();
        } elseif ($groupBy === 'year') {
            // Average scores by year
            $averageScores = Project::select('projects.year')
                ->leftJoin('evaluations', 'projects.id', '=', 'evaluations.project_id')
                ->groupBy('projects.year')
                ->selectRaw('
                    AVG(evaluations.technical_score) as avg_technical,
                    AVG(evaluations.design_score) as avg_design,
                    AVG(evaluations.documentation_score) as avg_documentation,
                    AVG(evaluations.presentation_score) as avg_presentation,
                    AVG(evaluations.total_score) as avg_total,
                    COUNT(DISTINCT evaluations.id) as evaluation_count
                ')
                ->orderBy('projects.year', 'desc')
                ->get();
        } else {
            // Overall average scores
            $averageScores = Evaluation::selectRaw('
                AVG(technical_score) as avg_technical,
                AVG(design_score) as avg_design,
                AVG(documentation_score) as avg_documentation,
                AVG(presentation_score) as avg_presentation,
                AVG(total_score) as avg_total,
                COUNT(*) as evaluation_count
            ')
            ->first();
        }

        return view('reports.average-scores', [
            'averageScores' => $averageScores,
            'groupBy' => $groupBy,
        ]);
    }

    /**
     * Count projects per advisor and display advisor workload.
     * Requirements: 13.3
     */
    public function advisorProjects(Request $request)
    {
        // Check authorization
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAdvisor() && !$user->isCommittee()) {
            abort(403, 'Unauthorized access to reports.');
        }

        // Get advisors with their project counts
        $advisorProjects = User::where('role', 'advisor')
            ->withCount('advisedProjects')
            ->orderBy('advised_projects_count', 'desc')
            ->get();

        // Calculate statistics
        $totalAdvisors = $advisorProjects->count();
        $totalProjects = $advisorProjects->sum('advised_projects_count');
        $averageProjectsPerAdvisor = $totalAdvisors > 0 ? round($totalProjects / $totalAdvisors, 2) : 0;

        // Find max and min workload
        $maxWorkload = $advisorProjects->max('advised_projects_count');
        $minWorkload = $advisorProjects->min('advised_projects_count');

        return view('reports.advisor-projects', [
            'advisorProjects' => $advisorProjects,
            'totalAdvisors' => $totalAdvisors,
            'totalProjects' => $totalProjects,
            'averageProjectsPerAdvisor' => $averageProjectsPerAdvisor,
            'maxWorkload' => $maxWorkload,
            'minWorkload' => $minWorkload,
        ]);
    }
}
