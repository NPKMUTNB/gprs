<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        Gate::authorize('manage-categories');

        $categories = Category::withCount('projects')->orderBy('name')->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        Gate::authorize('manage-categories');

        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-categories');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        // Log activity
        ActivityLogger::log('created_category', "Created category: {$category->name}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        Gate::authorize('manage-categories');

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        Gate::authorize('manage-categories');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        // Log activity
        ActivityLogger::log('updated_category', "Updated category: {$category->name}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('manage-categories');

        // Check if category is in use
        $projectCount = $category->projects()->count();
        
        if ($projectCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Cannot delete category. It is currently used by {$projectCount} project(s).");
        }

        $categoryName = $category->name;
        $category->delete();

        // Log activity
        ActivityLogger::log('deleted_category', "Deleted category: {$categoryName}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
