<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download(Submission $submission)
    {
        $task = $submission->task;
        $user = auth()->user();

        // Security Check: Only involved users (Admin, Reviewer, or specific Assignee) can access.
        if ($user->role_level == 2 && $task->assignee_id !== $user->id) {
            abort(403, 'Unauthorized. This is not your task.');
        }

        $path = $submission->file_url;

        if (!$path || !Storage::disk('submissions')->exists($path)) {
            abort(404, 'File not found.');
        }

        $ext      = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $slug     = \Illuminate\Support\Str::slug($task->title);
        $filename = "{$slug}_v{$submission->version}.{$ext}";

        $videoTypes = ['mp4', 'mov', 'avi', 'webm'];

        if (in_array($ext, $videoTypes)) {
            // Stream video (allows seeking in players)
            return response()->file(Storage::disk('submissions')->path($path));
        }

        return response()->download(Storage::disk('submissions')->path($path), $filename);
    }
}
