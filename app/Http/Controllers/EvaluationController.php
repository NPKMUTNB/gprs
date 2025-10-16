<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Evaluation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EvaluationController extends Controller
{
    /**
     * Show the evaluation form for a project.
     */
    public function create(Project $project)
    {
        // Check authorization using evaluate-project gate
        if (!Gate::allows('evaluate-project')) {
            abort(403, 'You are not authorized to evaluate projects.');
        }

        // Load project with necessary relationships
        $project->load(['creator', 'advisor', 'category']);

        return view('evaluations.create', compact('project'));
    }

    /**
     * Store a new evaluation for a project.
     */
    public function store(StoreEvaluationRequest $request, Project $project)
    {
        // Check authorization using evaluate-project gate
        if (!Gate::allows('evaluate-project')) {
            abort(403, 'You are not authorized to evaluate projects.');
        }

        // Create evaluation (total_score calculated automatically by model)
        $evaluation = $project->evaluations()->create([
            'evaluator_id' => auth()->id(),
            'technical_score' => $request->technical_score,
            'design_score' => $request->design_score,
            'documentation_score' => $request->documentation_score,
            'presentation_score' => $request->presentation_score,
            'comment' => $request->comment,
        ]);

        // Log activity
        ActivityLogger::log('evaluated_project', "Evaluated project '{$project->title_en}' (ID: {$project->id}) with total score: {$evaluation->total_score}");

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Evaluation submitted successfully.');
    }
}
