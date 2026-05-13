<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function createdTasks() { return $this->hasMany(Task::class, 'admin_id'); }
    public function assignedTasks() { return $this->hasMany(Task::class, 'assignee_id'); }
    public function submissions() { return $this->hasMany(Submission::class, 'production_id'); }
    public function reviews() { return $this->hasMany(Review::class, 'reviewer_id'); }
    public function taskLogs() { return $this->hasMany(TaskLog::class, 'user_id'); }
}
