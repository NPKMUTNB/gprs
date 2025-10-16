<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'project_id',
        'evaluator_id',
        'technical_score',
        'design_score',
        'documentation_score',
        'presentation_score',
        'total_score',
        'comment',
    ];

    /**
     * Boot method to auto-calculate total_score.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($evaluation) {
            $evaluation->total_score = (
                $evaluation->technical_score +
                $evaluation->design_score +
                $evaluation->documentation_score +
                $evaluation->presentation_score
            ) / 4;
        });
    }

    /**
     * Project being evaluated.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * User who evaluated the project.
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
