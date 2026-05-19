<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Submission;
use App\Models\TaskLog;
use App\Models\User;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();
        $production = User::where('username', 'modeling1')->first();
        $reviewer = User::where('username', 'reviewer1')->first();

        if (!$admin || !$production || !$reviewer) {
            return;
        }

        // Task 1: Completed
        $task1 = Task::create([
            'admin_id' => $admin->id,
            'assignee_id' => $production->id,
            'title' => 'Hero Character Modeling',
            'description' => 'Model the main character in high poly.',
            'deadline' => now()->addDays(2),
            'priority' => 'high',
            'status' => 'completed',
            'version' => 1
        ]);
        Submission::create([
            'task_id' => $task1->id,
            'production_id' => $production->id,
            'version' => 1,
            'blend_url' => 'dummy.blend',
            'video_url' => 'dummy.mov',
            'notes' => 'Here is the final high poly.'
        ]);
        TaskLog::create(['task_id' => $task1->id, 'user_id' => $admin->id, 'previous_status' => 'ready_for_admin', 'new_status' => 'completed', 'action_note' => 'Looks great, marking completed.']);

        // Task 2: Revision
        $task2 = Task::create([
            'admin_id' => $admin->id,
            'assignee_id' => $production->id,
            'title' => 'Enemy Orc Texturing',
            'description' => 'Apply realistic skin textures to the Orc.',
            'deadline' => now()->subDay(), // overdue
            'priority' => 'normal',
            'status' => 'revision',
            'version' => 1
        ]);
        Submission::create([
            'task_id' => $task2->id,
            'production_id' => $production->id,
            'version' => 1,
            'blend_url' => 'dummy.blend',
            'video_url' => 'dummy.mov',
            'notes' => 'First pass texture.'
        ]);
        TaskLog::create(['task_id' => $task2->id, 'user_id' => $reviewer->id, 'previous_status' => 'awaiting_review', 'new_status' => 'revision', 'action_note' => 'Revision requested: [00:15] Skin too clean.']);

        // Task 3: In Progress
        $task3 = Task::create([
            'admin_id' => $admin->id,
            'assignee_id' => $production->id,
            'title' => 'Weapon Rigging',
            'description' => 'Rig the assault rifle moving parts.',
            'deadline' => now()->addDays(5),
            'priority' => 'low',
            'status' => 'in_progress',
            'version' => 1
        ]);
        TaskLog::create(['task_id' => $task3->id, 'user_id' => $production->id, 'previous_status' => 'pending', 'new_status' => 'in_progress', 'action_note' => 'Started working.']);

        // Task 4: Awaiting Review
        $task4 = Task::create([
            'admin_id' => $admin->id,
            'assignee_id' => $production->id,
            'title' => 'Walk Cycle Animation',
            'description' => 'Animate standard walk cycle for NPC.',
            'deadline' => now()->addDays(1),
            'priority' => 'high',
            'status' => 'awaiting_review',
            'version' => 2
        ]);
        Submission::create([
            'task_id' => $task4->id,
            'production_id' => $production->id,
            'version' => 2,
            'blend_url' => 'dummy.blend',
            'video_url' => 'dummy.mov',
            'notes' => 'Fixed the foot sliding issue.'
        ]);
        TaskLog::create(['task_id' => $task4->id, 'user_id' => $production->id, 'previous_status' => 'revision', 'new_status' => 'awaiting_review', 'action_note' => 'Resubmitted fixed animation.']);

        // Task 5: Ready for Admin
        $task5 = Task::create([
            'admin_id' => $admin->id,
            'assignee_id' => $production->id,
            'title' => 'Lighting Scene 01',
            'description' => 'Set up moody lighting for the cave entrance.',
            'deadline' => now()->addDays(3),
            'priority' => 'normal',
            'status' => 'ready_for_admin',
            'version' => 1
        ]);
        Submission::create([
            'task_id' => $task5->id,
            'production_id' => $production->id,
            'version' => 1,
            'blend_url' => 'dummy.blend',
            'video_url' => 'dummy.mov',
            'notes' => 'First pass lighting.'
        ]);
        TaskLog::create(['task_id' => $task5->id, 'user_id' => $reviewer->id, 'previous_status' => 'awaiting_review', 'new_status' => 'ready_for_admin', 'action_note' => 'Approved by QC']);
    }
}
