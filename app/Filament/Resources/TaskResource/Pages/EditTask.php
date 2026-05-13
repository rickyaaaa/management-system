<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\TaskLog;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function afterSave(): void
    {
        $record = $this->record;
        $original = $record->getOriginal('status');
        $new = $record->status;

        if ($original !== $new) {
            TaskLog::create([
                'task_id'         => $record->id,
                'user_id'         => auth()->id(),
                'previous_status' => $original,
                'new_status'      => $new,
                'action_note'     => 'Status updated by admin.',
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
