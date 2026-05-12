<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function submission() { return $this->belongsTo(Submission::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
}
