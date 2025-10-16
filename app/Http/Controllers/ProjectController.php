<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Project;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with search and filters.
     */
    public function index(Request $request)
    {
        $query = Project::with(['creator', 'advisor', 'category', 'tags']);

        // Apply guest access restrictions - show only completed/approved projects to guests
        if (!auth()->check()) {
            $query->whereIn('status', ['completed', 'approved']);
        }

        // Search by title or abstract
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_th', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%")
                  ->orWhereHas('tags', function ($tagQuery) use ($search) {
                      $tagQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by advisor
        if ($request->filled('advisor_id')) {
            $query->where('advisor_id', $request->advisor_id);
        }

        // Filter by creator (for students to see their own projects)
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Filter by status (only for authenticated users)
        if ($request->filled('status') && auth()->check()) {
            $query->where('status', $request->status);
        }

        $projects = $query->latest()->paginate(20);

        // Get filter options
        $categories = Category::all();
        $advisors = User::where('role', 'advisor')->get();
        $years = Project::distinct()->pluck('year')->sort()->values();

        return view('projects.index', compact('projects', 'categories', 'advisors', 'years'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $categories = Category::all();
        $advisors = User::where('role', 'advisor')->get();
        $tags = \App\Models\Tag::all();

        return view('projects.create', compact('categories', 'advisors', 'tags'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_th' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'abstract' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',
            'semester' => 'required|in:1,2,3',
            'category_id' => 'required|exists:categories,id',
            'advisor_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $project = Project::create([
            'title_th' => $validated['title_th'],
            'title_en' => $validated['title_en'],
            'abstract' => $validated['abstract'],
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'category_id' => $validated['category_id'],
            'advisor_id' => $validated['advisor_id'],
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Attach tags if provided
        if (!empty($validated['tags'])) {
            $project->tags()->attach($validated['tags']);
        }

        // Log activity
        ActivityLogger::log('created_project', "Created project: {$project->title_en} (ID: {$project->id})");

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        // Load all relationships
        $project->load([
            'creator',
            'advisor',
            'category',
            'tags',
            'members.user',
            'files',
            'evaluations.evaluator',
            'comments.user'
        ]);

        // Check authorization for non-published projects
        if (!in_array($project->status, ['completed', 'approved']) && !auth()->check()) {
            abort(403, 'Unauthorized access to this project.');
        }

        // Additional authorization check for draft/submitted projects
        if (auth()->check() && !in_array($project->status, ['completed', 'approved'])) {
            $user = auth()->user();
            $canView = $user->id === $project->created_by
                || $user->id === $project->advisor_id
                || $user->role === 'admin'
                || $user->role === 'committee'
                || $project->members->contains('user_id', $user->id);

            if (!$canView) {
                abort(403, 'Unauthorized access to this project.');
            }
        }

        // Get students for team member form
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('projects.show', compact('project', 'students'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $categories = Category::all();
        $advisors = User::where('role', 'advisor')->get();
        $tags = \App\Models\Tag::all();
        $students = User::where('role', 'student')->orderBy('name')->get();
        $project->load('tags', 'members.user');

        return view('projects.edit', compact('project', 'categories', 'advisors', 'tags', 'students'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title_th' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'abstract' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',
            'semester' => 'required|in:1,2,3',
            'category_id' => 'required|exists:categories,id',
            'advisor_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $project->update([
            'title_th' => $validated['title_th'],
            'title_en' => $validated['title_en'],
            'abstract' => $validated['abstract'],
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'category_id' => $validated['category_id'],
            'advisor_id' => $validated['advisor_id'],
        ]);

        // Sync tags
        if (isset($validated['tags'])) {
            $project->tags()->sync($validated['tags']);
        } else {
            $project->tags()->detach();
        }

        // Log activity
        ActivityLogger::log('updated_project', "Updated project: {$project->title_en} (ID: {$project->id})");

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        // Log activity
        ActivityLogger::log('deleted_project', "Deleted project: {$project->title_en} (ID: {$project->id})");

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Submit a project for review (change status from draft to submitted).
     */
    public function submit(Project $project)
    {
        // Check if user is the creator
        if (auth()->id() !== $project->created_by) {
            abort(403, 'Only the project creator can submit the project.');
        }

        // Check if project is in draft status
        if ($project->status !== 'draft') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Only draft projects can be submitted.');
        }

        $project->update(['status' => 'submitted']);

        // Log activity
        ActivityLogger::log('submitted_project', "Submitted project: {$project->title_en} (ID: {$project->id})");

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project submitted successfully.');
    }

    /**
     * Approve a submitted project.
     */
    public function approve(Project $project)
    {
        $this->authorize('approve', $project);

        // Check if project is in submitted status
        if ($project->status !== 'submitted') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Only submitted projects can be approved.');
        }

        $project->update(['status' => 'approved']);

        // Log activity
        ActivityLogger::log('approved_project', "Approved project: {$project->title_en} (ID: {$project->id})");

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project approved successfully.');
    }

    /**
     * Reject a submitted project.
     */
    public function reject(Project $project)
    {
        $this->authorize('approve', $project);

        // Check if project is in submitted status
        if ($project->status !== 'submitted') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Only submitted projects can be rejected.');
        }

        $project->update(['status' => 'rejected']);

        // Log activity
        ActivityLogger::log('rejected_project', "Rejected project: {$project->title_en} (ID: {$project->id})");

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project rejected. The student can now edit and resubmit.');
    }
}
