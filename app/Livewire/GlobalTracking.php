<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class GlobalTracking extends Component
{
    public Task $task;

    public function render()
    {
        return view('livewire.global-tracking');
    }
}
