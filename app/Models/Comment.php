<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'content',
    ];

    /**
     * Project this comment belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * User who made this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
