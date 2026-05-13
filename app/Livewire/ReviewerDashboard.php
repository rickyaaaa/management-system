<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Review;
use App\Models\TaskLog;
use App\Models\User;
use App\Notifications\TaskNotification;

class ReviewerDashboard extends Component
{
    public $reviewNotes;
    public $selectedSubmissionId;
    public $showModal = false;
    public $currentTaskId;
    public $reviewTimestamp = '';

    public $viewingSubmissionId = [];
    public $selectedReviewTaskId = null;

    public function selectTask($taskId)
    {
        $this->selectedReviewTaskId = $taskId;
    }

    public function closeTask()
    {
        $this->selectedReviewTaskId = null;
    }

    public function switchVersion($taskId, $submissionId)
    {
        $this->viewingSubmissionId[$taskId] = $submissionId;
    }

    public function approve($submissionId, $taskId)
    {
        Review::create([
            'submission_id' => $submissionId,
            'reviewer_id' => auth()->id(),
            'status' => 'approved',
        ]);

        $task = Task::find($taskId);
        $previousStatus = $task->status;
        $task->update(['status' => 'ready_for_admin']);

        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'previous_status' => $previousStatus,
            'new_status' => 'ready_for_admin',
            'action_note' => 'Approved by QC',
        ]);

        $admins = User::where('role_level', 1)->get();
        /** @var \App\Models\User $admin */
        foreach($admins as $admin) {
            $admin->notify(new TaskNotification('Task ready for final approval: ' . $task->title, $task->id));
        }

        $this->selectedReviewTaskId = null;
        $this->dispatch('notify', message: 'Submission Approved.');
    }

    public function openRevisionModal($submissionId, $taskId)
    {
        $this->selectedSubmissionId = $submissionId;
        $this->currentTaskId = $taskId;
        $this->showModal = true;
    }

    public function submitRevision()
    {
        $this->validate([
            'reviewNotes' => 'required|string',
            'reviewTimestamp' => 'nullable|string|max:10',
        ]);

        $finalNote = $this->reviewNotes;
        if (!empty($this->reviewTimestamp)) {
            $finalNote = '[' . $this->reviewTimestamp . '] ' . $finalNote;
        }

        Review::create([
            'submission_id' => $this->selectedSubmissionId,
            'reviewer_id' => auth()->id(),
            'status' => 'rejected',
            'feedback' => $finalNote,
        ]);

        $task = Task::find($this->currentTaskId);
        $previousStatus = $task->status;
        $task->update(['status' => 'revision']);

        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'previous_status' => $previousStatus,
            'new_status' => 'revision',
            'action_note' => 'Revision requested: ' . $finalNote,
        ]);

        /** @var \App\Models\User $assignee */
        $assignee = $task->assignee;
        $assignee->notify(new TaskNotification('Revision requested for: ' . $task->title, $task->id));

        $this->showModal = false;
        $this->reset(['reviewNotes', 'reviewTimestamp', 'selectedSubmissionId', 'currentTaskId']);
        $this->selectedReviewTaskId = null;
        $this->dispatch('notify', message: 'Revision requested.');
    }

    public function render()
    {
        $tasks = Task::where('status', 'awaiting_review')
            ->with(['submissions' => function($q) {
                $q->latest();
            }, 'assignee', 'logs' => function($q) {
                $q->latest();
            }, 'logs.user'])
            ->get();

        return view('livewire.reviewer-dashboard', compact('tasks'));
    }
}
