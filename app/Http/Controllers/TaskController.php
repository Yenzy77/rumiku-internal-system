<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Property;
use App\Models\TaskValue;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'asc');
        
        // Eager load all values with their properties
        $allTasks = Task::with(['values.property', 'creator'])->get();

        // Get columns to display (ordered by sort_order)
        $properties = Property::orderBy('sort_order')->get();

        // Find the status property
        $statusProperty = $properties->firstWhere('slug', 'status');
        
        // Find the due_date property for sorting
        $dueDateProperty = $properties->firstWhere('slug', 'due_date');

        // Handle sorting
        switch ($sort) {
            case 'title_asc':
                $allTasks = $allTasks->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE);
                break;
            case 'title_desc':
                $allTasks = $allTasks->sortByDesc('title', SORT_NATURAL | SORT_FLAG_CASE);
                break;
            case 'newest':
                $allTasks = $allTasks->sortByDesc('created_at');
                break;
            case 'oldest':
                $allTasks = $allTasks->sortBy('created_at');
                break;
            case 'deadline_desc':
                $allTasks = $allTasks->sortBy(function ($task) use ($dueDateProperty) {
                    if (!$dueDateProperty) return '0000-00-00';
                    $dateValue = $task->values->firstWhere('property_id', $dueDateProperty->id);
                    return $dateValue?->value ?: '0000-00-00';
                }, SORT_REGULAR, true);
                break;
            case 'deadline_asc':
            default:
                $allTasks = $allTasks->sortBy(function ($task) use ($dueDateProperty) {
                    if (!$dueDateProperty) return 'z';
                    $dateValue = $task->values->firstWhere('property_id', $dueDateProperty->id);
                    return $dateValue?->value ?: 'z';
                });
                break;
        }

        // Group by status value (ensure ALL status columns appear on Kanban Board)
        $tasks = collect();
        if ($statusProperty) {
            $statusOptions = $statusProperty->options ?? [];
            
            // Loop through all options and initialize collections (empty or populated)
            foreach ($statusOptions as $option) {
                $label = is_array($option) ? ($option['label'] ?? '') : $option;
                $grouped = $allTasks->filter(function ($task) use ($statusProperty, $label) {
                    $statusValue = $task->values->firstWhere('property_id', $statusProperty->id);
                    return $statusValue && $statusValue->value === $label;
                });
                
                // Always add the column, even if empty, for the Kanban view
                $tasks[$label] = $grouped;
            }
            
            // Also capture tasks with no status
            $noStatus = $allTasks->filter(function ($task) use ($statusProperty) {
                $statusValue = $task->values->firstWhere('property_id', $statusProperty->id);
                return !$statusValue || !$statusValue->value;
            });
            if ($noStatus->isNotEmpty()) {
                $tasks['No Status'] = $noStatus;
            }
        } else {
            $tasks['All Tasks'] = $allTasks;
        }

        // Calculate Stats for the UI
        $now = now()->toDateString();
        $stats = [
            'total' => $allTasks->count(),
            'completed' => $allTasks->filter(function ($t) use ($statusProperty) {
                $v = $t->values->firstWhere('property_id', $statusProperty?->id)?->value;
                return in_array(strtolower($v ?? ''), ['done', 'completed', 'selesai']);
            })->count(),
            'overdue' => $allTasks->filter(function ($t) use ($dueDateProperty, $statusProperty, $now) {
                $statusVal = $t->values->firstWhere('property_id', $statusProperty?->id)?->value;
                if (in_array(strtolower($statusVal ?? ''), ['done', 'completed', 'selesai'])) return false;
                
                $dateVal = $t->values->firstWhere('property_id', $dueDateProperty?->id)?->value;
                return $dateVal && $dateVal < $now;
            })->count(),
            'active' => $allTasks->count() - $allTasks->filter(function ($t) use ($statusProperty) {
                $v = $t->values->firstWhere('property_id', $statusProperty?->id)?->value;
                return in_array(strtolower($v ?? ''), ['done', 'completed', 'selesai', 'to do', 'todo', 'backlog']);
            })->count()
        ];
        
        return view('tasks.index', compact('tasks', 'sort', 'properties', 'statusProperty', 'stats'));
    }
}
