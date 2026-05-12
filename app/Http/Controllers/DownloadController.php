<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download(Submission $submission, $type)
    {
        $task = $submission->task;
        $user = auth()->user();

        // Security Check: Only involved users (Admin, Reviewer, or specific Assignee) can access.
        if ($user->role_level == 2 && $task->assignee_id !== $user->id) {
            abort(403, 'Unauthorized. This is not your task.');
        }

        $path = $type === 'blend' ? $submission->file_blend_url : $submission->file_mov_url;
        
        if (!Storage::disk('submissions')->exists($path)) {
            abort(404, 'File not found.');
        }

        if ($type === 'blend') {
            return Storage::disk('submissions')->download($path);
        } else {
            // Stream the video (allows seeking in custom video players)
            return response()->file(Storage::disk('submissions')->path($path));
        }
    }
}
