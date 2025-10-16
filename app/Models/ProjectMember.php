<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
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
        'role_in_team',
    ];

    /**
     * Project this member belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * User who is a member of the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
