<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\TaskLog;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['admin_id'] = auth()->id();
        $data['status'] = 'pending';
        $data['version'] = 1;

        $task = parent::handleRecordCreation($data);

        TaskLog::create([
            'task_id'         => $task->id,
            'user_id'         => auth()->id(),
            'previous_status' => null,
            'new_status'      => 'pending',
            'action_note'     => 'Task created and assigned.',
        ]);

        return $task;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
