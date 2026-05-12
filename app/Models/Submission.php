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

    public function getBlendSizeAttribute()
    {
        try {
            $bytes = \Illuminate\Support\Facades\Storage::disk('submissions')->size($this->file_blend_url);
            return \Illuminate\Support\Number::fileSize($bytes);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    public function getMovSizeAttribute()
    {
        try {
            $bytes = \Illuminate\Support\Facades\Storage::disk('submissions')->size($this->file_mov_url);
            return \Illuminate\Support\Number::fileSize($bytes);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}
