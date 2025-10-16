<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectFileController extends Controller
{
    /**
     * Store a newly uploaded file.
     */
    public function store(Request $request, Project $project)
    {
        // Check authorization - only project creator, team members, or admin can upload
        $user = $request->user();
        if (!$this->canManageFiles($user, $project)) {
            abort(403, 'Unauthorized to upload files to this project.');
        }

        // Validate file upload
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'file_type' => 'required|in:proposal,report,presentation,code,other',
        ]);

        try {
            $uploadedFile = $request->file('file');
            $fileType = $request->input('file_type');

            // Generate unique filename
            $originalName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $uniqueName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;

            // Store file in storage/app/public/projects/{project_id}/{file_type}/
            $filePath = $uploadedFile->storeAs(
                "public/projects/{$project->id}/{$fileType}",
                $uniqueName
            );

            // Save file metadata to database
            $projectFile = ProjectFile::create([
                'project_id' => $project->id,
                'file_name' => $originalName,
                'file_type' => $fileType,
                'file_path' => $filePath,
            ]);

            // Log activity
            ActivityLogger::log('uploaded_file', "Uploaded file '{$originalName}' to project '{$project->title_en}' (ID: {$project->id})", $user->id);

            return redirect()
                ->route('projects.show', $project)
                ->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('projects.show', $project)
                ->with('error', 'Failed to upload file. Please try again.');
        }
    }

    /**
     * Delete a file.
     */
    public function destroy(Request $request, Project $project, ProjectFile $file)
    {
        // Check authorization
        $user = $request->user();
        if (!$this->canManageFiles($user, $project)) {
            abort(403, 'Unauthorized to delete files from this project.');
        }

        // Verify file belongs to project
        if ($file->project_id !== $project->id) {
            abort(404, 'File not found.');
        }

        $fileName = $file->file_name;

        // Delete file from storage
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }

        // Delete database record
        $file->delete();

        // Log activity
        ActivityLogger::log('deleted_file', "Deleted file '{$fileName}' from project '{$project->title_en}' (ID: {$project->id})", $user->id);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'File deleted successfully.');
    }

    /**
     * Download a file.
     */
    public function download(Request $request, Project $project, ProjectFile $file)
    {
        // Check authorization - users can download files from published projects or their own projects
        $user = $request->user();
        
        // Allow download if project is published/completed or user has access
        if (!$this->canViewFile($user, $project)) {
            abort(403, 'Unauthorized to download this file.');
        }

        // Verify file belongs to project
        if ($file->project_id !== $project->id) {
            abort(404, 'File not found.');
        }

        // Check if file exists in storage
        if (!Storage::exists($file->file_path)) {
            abort(404, 'File not found in storage.');
        }

        // Log activity
        if ($user) {
            ActivityLogger::log('downloaded_file', "Downloaded file '{$file->file_name}' from project '{$project->title_en}' (ID: {$project->id})", $user->id);
        }

        // Return file download response
        return Storage::download($file->file_path, $file->file_name);
    }

    /**
     * Check if user can manage files (upload/delete).
     */
    private function canManageFiles($user, Project $project): bool
    {
        if (!$user) {
            return false;
        }

        // Admin can manage all files
        if ($user->role === 'admin') {
            return true;
        }

        // Project creator can manage files
        if ($user->id === $project->created_by) {
            return true;
        }

        // Advisor can manage files
        if ($user->id === $project->advisor_id) {
            return true;
        }

        // Team members can manage files
        $isMember = $project->members()->where('user_id', $user->id)->exists();
        if ($isMember) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can view/download files.
     */
    private function canViewFile($user, Project $project): bool
    {
        // Published/completed projects are viewable by all authenticated users
        if (in_array($project->status, ['approved', 'completed'])) {
            return true;
        }

        // For non-published projects, check if user has access
        if ($user) {
            return $this->canManageFiles($user, $project) || $user->role === 'committee';
        }

        return false;
    }
}
