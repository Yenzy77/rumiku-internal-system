<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'asc');
        
        $query = Task::with('assignee');
        
        if ($sort === 'desc') {
            $query->orderByRaw('due_date IS NULL, due_date DESC');
        } else {
            $query->orderByRaw('due_date IS NULL, due_date ASC');
        }
        
        $tasks = $query->get()->groupBy('status');
        
        return view('tasks.index', compact('tasks', 'sort'));
    }

    // Methods create and store are now handled by TaskForm Livewire component

    // Method store is now handled by TaskForm Livewire component

    // Method edit is now handled by TaskForm Livewire component

    // Method update is now handled by TaskForm Livewire component

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
