<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class TaskForm extends Component
{
    public $taskId;
    public $title;
    public $description;
    public $assigned_to;
    public $status = 'pending';
    public $due_date;
    
    public $isOpen = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'assigned_to' => 'required|exists:users,id',
        'status' => 'required|string',
        'due_date' => 'nullable|date',
    ];

    #[On('openTaskForm')]
    public function loadTask($id = null)
    {
        $this->resetErrorBag();
        
        if ($id) {
            $task = Task::findOrFail($id);
            $this->taskId = $task->id;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->assigned_to = $task->assigned_to;
            $this->status = $task->status;
            $this->due_date = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '';
        } else {
            $this->resetForm();
        }

        $this->isOpen = true;
    }

    #[On('createTask')]
    public function resetForm()
    {
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->assigned_to = '';
        $this->status = 'pending';
        $this->due_date = '';
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->taskId) {
            $task = Task::find($this->taskId);
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
                'assigned_to' => $this->assigned_to,
                'status' => $this->status,
                'due_date' => $this->due_date,
            ]);
            $message = 'Task updated successfully.';
        } else {
            Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'assigned_to' => $this->assigned_to,
                'status' => $this->status,
                'due_date' => $this->due_date,
            ]);
            $message = 'Task created successfully.';
        }

        $this->isOpen = false;
        $this->dispatch('taskSaved', $message);
    }

    public function delete()
    {
        if ($this->taskId) {
            Task::find($this->taskId)->delete();
            $this->isOpen = false;
            $this->dispatch('taskSaved', 'Task deleted safely.');
        }
    }

    public function render()
    {
        return view('livewire.tasks.task-form', [
            'users' => User::all()
        ]);
    }
}
