<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Download or stream a submission file.
     *
     * ?type=blend  → download the .blend file
     * ?type=video  → stream the video file
     * (default)    → prefers video if available, then blend
     */
    public function download(Submission $submission, Request $request)
    {
        $task = $submission->task;
        $user = auth()->user();

        // Security: Level-2 users can only access their own tasks
        if ($user->role_level == 2 && $task->assignee_id !== $user->id) {
            abort(403, 'Unauthorized. This is not your task.');
        }

        $type = $request->query('type', null);

        if ($type === 'blend') {
            $path = $submission->blend_url;
        } elseif ($type === 'video') {
            $path = $submission->video_url;
        } else {
            // Auto: prefer video for preview experience, fall back to blend
            $path = $submission->video_url ?? $submission->blend_url;
        }

        if (!$path || !Storage::disk('submissions')->exists($path)) {
            abort(404, 'File not found.');
        }

        $ext      = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $slug     = \Illuminate\Support\Str::slug($task->title);
        $filename = "{$slug}_v{$submission->version}.{$ext}";

        // Always force download — no inline streaming
        return response()->download(Storage::disk('submissions')->path($path), $filename);
    }
}
