<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a new comment for a project.
     */
    public function store(Request $request, Project $project)
    {
        // Check authentication
        if (!auth()->check()) {
            abort(403, 'You must be logged in to comment.');
        }

        // Validate comment content
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Save comment with user_id and timestamp (timestamps handled automatically)
        $comment = $project->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        // Log activity
        ActivityLogger::log('added_comment', "Added comment to project '{$project->title_en}' (ID: {$project->id})");

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        // Check authorization (admin only)
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can delete comments.');
        }

        $projectId = $comment->project_id;
        $projectTitle = $comment->project->title_en;

        // Delete comment
        $comment->delete();

        // Log activity
        ActivityLogger::log('deleted_comment', "Deleted comment from project '{$projectTitle}' (ID: {$projectId})");

        return redirect()
            ->route('projects.show', $projectId)
            ->with('success', 'Comment deleted successfully.');
    }
}
