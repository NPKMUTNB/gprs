<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index()
    {
        Gate::authorize('manage-categories');

        $tags = Tag::withCount('projects')->orderBy('name')->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-categories');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags',
        ]);

        $tag = Tag::create($validated);

        // Log activity
        ActivityLogger::log('created_tag', "Created tag: {$tag->name}");

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(Tag $tag)
    {
        Gate::authorize('manage-categories');

        // Detach from all projects before deleting
        $tag->projects()->detach();
        
        $tagName = $tag->name;
        $tag->delete();

        // Log activity
        ActivityLogger::log('deleted_tag', "Deleted tag: {$tagName}");

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
