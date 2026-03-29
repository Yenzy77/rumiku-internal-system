<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskStatusUpdate extends Component
{
    public Task $task;
    public string $status;

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->status = $task->status;
    }

    public function updatedStatus($value)
    {
        $this->task->status = $value;
        $this->task->save();
        
        $this->dispatch('task-updated', [
            'taskId' => $this->task->id, 
            'status' => $value
        ]);
    }

    public function render()
    {
        return view('livewire.task-status-update');
    }
}
