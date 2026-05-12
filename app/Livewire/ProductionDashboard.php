<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task;
use App\Models\Submission;
use App\Models\TaskLog;
use App\Models\User;
use App\Notifications\TaskNotification;

class ProductionDashboard extends Component
{
    use WithFileUploads;

    public $selectedTaskId;
    public $file_blend;
    public $file_mov;
    public $notes;

    public function selectTask($id)
    {
        $this->selectedTaskId = $id;
    }

    public function submitWork()
    {
        $this->validate([
            'selectedTaskId' => 'required|exists:tasks,id',
            'file_blend' => 'required|file|max:102400', // 100MB max
            'file_mov' => 'required|mimes:mp4,mov,avi|max:102400', // 100MB max
            'notes' => 'nullable|string',
        ]);

        $task = Task::findOrFail($this->selectedTaskId);

        // Store files
        $blendPath = $this->file_blend->store(path: 'blend', disk: 'submissions');
        $movPath = $this->file_mov->store(path: 'mov', disk: 'submissions');

        $version = $task->submissions()->count() + 1;

        $submission = Submission::create([
            'task_id' => $task->id,
            'production_id' => auth()->id(),
            'version' => $version,
            'file_blend_url' => $blendPath,
            'file_mov_url' => $movPath,
            'notes' => $this->notes,
        ]);

        $previousStatus = $task->status;
        $task->update([
            'status' => 'awaiting_review',
            'version' => $version
        ]);

        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'previous_status' => $previousStatus,
            'new_status' => 'awaiting_review',
            'action_note' => 'Work submitted (v'.$version.')',
        ]);

        $reviewers = User::where('role_level', 3)->get();
        /** @var \App\Models\User $reviewer */
        foreach($reviewers as $reviewer) {
            $reviewer->notify(new TaskNotification('Work submitted for review: ' . $task->title, $task->id));
        }

        $this->reset(['selectedTaskId', 'file_blend', 'file_mov', 'notes']);
        $this->dispatch('notify', message: 'Work successfully submitted for review.');
    }

    public function render()
    {
        return view('livewire.production-dashboard', [
            'tasks' => Task::where('assignee_id', auth()->id())
                ->whereIn('status', ['pending', 'in_progress', 'revision'])
                ->get()
        ]);
    }
}
