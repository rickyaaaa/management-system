<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assignee_id'); }
    public function submissions() { return $this->hasMany(Submission::class); }
    public function logs() { return $this->hasMany(TaskLog::class); }
}
