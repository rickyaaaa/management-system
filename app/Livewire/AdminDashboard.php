<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskLog;
use App\Notifications\TaskNotification;

class AdminDashboard extends Component
{
    public $title = '';
    public $description = '';
    public $assignee_id = '';
    public $deadline = '';
    public $priority = 'normal';
    public $detailTaskId = null;

    public function viewDetails($id)
    {
        $this->detailTaskId = $id;
    }

    public function closeDetails()
    {
        $this->detailTaskId = null;
    }

    public function markAsCompleted($taskId)
    {
        $task = Task::find($taskId);
        $task->update(['status' => 'completed']);
        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'previous_status' => 'ready_for_admin',
            'new_status' => 'completed',
            'action_note' => 'Task verified and marked as Completed by Admin.',
        ]);
        
        /** @var \App\Models\User $assignee */
        $assignee = $task->assignee;
        $assignee->notify(new TaskNotification('Your task "' . $task->title . '" has been Completed!', $task->id));
        
        $this->dispatch('notify', message: 'Task marked as Completed.');
    }

    public function createTask()
    {
        $this->validate([
            'title' => 'required|string|max:150',
            'assignee_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,normal,high',
        ]);

        $task = Task::create([
            'admin_id' => auth()->id(),
            'assignee_id' => $this->assignee_id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'priority' => $this->priority,
            'status' => 'pending',
        ]);

        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'new_status' => 'pending',
            'action_note' => 'Task created by Admin',
        ]);

        /** @var \App\Models\User $assignee */
        $assignee = User::find($this->assignee_id);
        $assignee->notify(new TaskNotification('New task assigned: ' . $this->title, $task->id));

        $this->reset(['title', 'description', 'assignee_id', 'deadline', 'priority']);
        $this->dispatch('notify', message: 'Task successfully created.');
    }

    public function render()
    {
        $tasksQuery = Task::with('assignee')->latest()->get();
        $detailTask = $this->detailTaskId ? Task::with(['submissions' => function($q){
            $q->latest();
        }, 'logs' => function($q){
            $q->latest();
        }, 'logs.user', 'assignee'])->find($this->detailTaskId) : null;

        $stats = [
            'active' => Task::whereIn('status', ['pending', 'in_progress', 'revision'])->count(),
            'awaiting_review' => Task::where('status', 'awaiting_review')->count(),
            'completed' => Task::where('status', 'completed')->count(),
        ];
        
        $topSpecialist = User::where('role_level', 2)
            ->withCount(['tasks' => function($q) {
                $q->where('status', 'completed');
            }])
            ->orderByDesc('tasks_count')
            ->first();

        return view('livewire.admin-dashboard', [
            'productionUsers' => User::where('role_level', 2)->get(),
            'tasks' => $tasksQuery,
            'detailTask' => $detailTask,
            'stats' => $stats,
            'topSpecialist' => $topSpecialist,
        ]);
    }
}
