<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'project_id',
        'file_name',
        'file_type',
        'file_path',
    ];

    /**
     * Project this file belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the download URL for this file.
     */
    public function getDownloadUrl(): string
    {
        return route('projects.files.download', [
            'project' => $this->project_id,
            'file' => $this->id
        ]);
    }

    /**
     * Get the file size in bytes.
     */
    public function getFileSizeBytes(): ?int
    {
        if (Storage::exists($this->file_path)) {
            return Storage::size($this->file_path);
        }

        return null;
    }

    /**
     * Get the file size in human-readable format.
     */
    public function getFileSize(): string
    {
        $bytes = $this->getFileSizeBytes();
        
        if ($bytes === null) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
