<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title_th',
        'title_en',
        'abstract',
        'year',
        'semester',
        'status',
        'category_id',
        'created_by',
        'advisor_id',
    ];

    /**
     * Category this project belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * User who created this project.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Advisor assigned to this project.
     */
    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    /**
     * Team members of this project.
     */
    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    /**
     * Files attached to this project.
     */
    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    /**
     * Evaluations for this project.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Comments on this project.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Tags associated with this project (many-to-many).
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'project_tag');
    }

    /**
     * Scope to filter published projects.
     */
    public function scopePublished($query)
    {
        return $query->whereIn('status', ['approved', 'completed']);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by year.
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Check if project can be edited by the given user.
     */
    public function canBeEditedBy(User $user): bool
    {
        // Student can edit only their own draft projects
        if ($user->id === $this->created_by && $this->status === 'draft') {
            return true;
        }

        // Advisor can edit projects they advise
        if ($user->id === $this->advisor_id && $user->isAdvisor()) {
            return true;
        }

        // Admin can edit any project
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Check if project can be deleted by the given user.
     */
    public function canBeDeletedBy(User $user): bool
    {
        // Only draft projects can be deleted by owner
        if ($user->id === $this->created_by && $this->status === 'draft') {
            return true;
        }

        // Admin can delete any project
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Calculate average evaluation score.
     */
    public function averageScore(): ?float
    {
        $average = $this->evaluations()->avg('total_score');
        
        return $average ? round($average, 2) : null;
    }
}
