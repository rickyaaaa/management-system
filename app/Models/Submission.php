<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function task() { return $this->belongsTo(Task::class); }
    public function production() { return $this->belongsTo(User::class, 'production_id'); }
    public function reviews() { return $this->hasMany(Review::class); }

    /**
     * Returns the primary file path (blend first, then video) for size calculation.
     */
    public function getFileSizeAttribute()
    {
        $path = $this->blend_url ?? $this->video_url;
        if (!$path) return 'Unknown';
        try {
            $bytes = \Illuminate\Support\Facades\Storage::disk('submissions')->size($path);
            return \Illuminate\Support\Number::fileSize($bytes);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Returns true if the submission has at least one file.
     */
    public function hasAnyFile(): bool
    {
        return !empty($this->blend_url) || !empty($this->video_url);
    }
}

