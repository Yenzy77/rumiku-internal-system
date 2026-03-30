<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Models\Property;
use App\Models\TaskValue;
use Livewire\Component;
use Livewire\Attributes\On;

class TaskForm extends Component
{
    public $taskId;
    public $title;
    public $propertyValues = []; // [property_id => value]
    
    public $isOpen = false;

    // For managing properties
    public $showAddProperty = false;
    public $newPropertyName = '';
    public $newPropertyType = 'text';
    public $newPropertyOptions = '';
    
    // For adding new options directly within form dropdowns
    public $newOptions = []; // [property_id => 'new option string']

    public function mount()
    {
        $this->loadPropertyDefaults();
    }

    #[On('openTaskForm')]
    public function loadTask($id = null)
    {
        $this->resetErrorBag();
        
        if ($id) {
            $task = Task::with('values.property')->findOrFail($id);
            $this->taskId = $task->id;
            $this->title = $task->title;
            
            // Load all property values
            $this->propertyValues = [];
            $properties = Property::orderBy('sort_order')->get();
            foreach ($properties as $property) {
                $taskValue = $task->values->firstWhere('property_id', $property->id);
                $value = $taskValue?->value;
                
                // Decode multi_select and person values: handle JSON arrays AND legacy single-value strings
                if (in_array($property->type, ['multi_select', 'person'])) {
                    if (!$value) {
                        $this->propertyValues[$property->id] = [];
                    } else {
                        $decoded = json_decode($value, true);
                        if (is_array($decoded)) {
                            $this->propertyValues[$property->id] = $decoded;
                        } else {
                            // It's a single value (string/number), wrap it in an array for multi-assignee compatibility
                            $this->propertyValues[$property->id] = [(string)$value];
                        }
                    }
                } else {
                    $this->propertyValues[$property->id] = $value ?? '';
                }
            }
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
        $this->propertyValues = [];
        
        // Initialize with empty values for all properties
        $properties = Property::orderBy('sort_order')->get();
        foreach ($properties as $property) {
            if (in_array($property->type, ['multi_select', 'person'])) {
                $this->propertyValues[$property->id] = [];
            } elseif ($property->type === 'checkbox') {
                $this->propertyValues[$property->id] = '0';
            } else {
                $this->propertyValues[$property->id] = '';
            }
        }
        
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
        ]);

        if ($this->taskId) {
            $task = Task::find($this->taskId);
            $task->update(['title' => $this->title]);
            $message = 'Task updated successfully.';
        } else {
            $task = Task::create([
                'title' => $this->title,
                'created_by' => null, // No auth yet
            ]);
            $message = 'Task created successfully.';
        }

        // Save all property values
        $properties = Property::all();
        foreach ($properties as $property) {
            $value = $this->propertyValues[$property->id] ?? null;
            
            // Encode arrays to JSON for multi_select and person
            if (in_array($property->type, ['multi_select', 'person']) && is_array($value)) {
                $value = !empty($value) ? json_encode($value) : null;
            }

            // Skip empty values (don't create empty records)
            if ($value === '' || $value === null) {
                // Delete existing value if it was cleared
                TaskValue::where('task_id', $task->id)
                    ->where('property_id', $property->id)
                    ->delete();
                continue;
            }

            TaskValue::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'property_id' => $property->id,
                ],
                ['value' => $value]
            );
        }

        $this->isOpen = false;
        $this->dispatch('taskSaved', $message);
    }

    public function delete()
    {
        if ($this->taskId) {
            Task::find($this->taskId)->delete(); // Cascade deletes task_values
            $this->isOpen = false;
            $this->dispatch('taskSaved', 'Task deleted safely.');
        }
    }

    public function toggleMultiSelect($propertyId, $option)
    {
        if (!isset($this->propertyValues[$propertyId]) || !is_array($this->propertyValues[$propertyId])) {
            $this->propertyValues[$propertyId] = [];
        }

        if (in_array($option, $this->propertyValues[$propertyId])) {
            $this->propertyValues[$propertyId] = array_values(
                array_filter($this->propertyValues[$propertyId], fn($v) => (string)$v !== (string)$option) // cast to string for user id matching
            );
        } else {
            $this->propertyValues[$propertyId][] = $option;
        }
    }

    public function addNewOption($propertyId)
    {
        $property = Property::find($propertyId);
        if (!$property) return;

        $newVal = trim($this->newOptions[$propertyId] ?? '');
        if (empty($newVal)) return;

        $options = is_array($property->options) ? $property->options : [];
        
        if ($property->type === 'status') {
            // Check if already exists
            foreach ($options as $opt) {
                if (is_array($opt) && strtolower($opt['label'] ?? '') === strtolower($newVal)) {
                    $this->propertyValues[$propertyId] = $opt['label'];
                    $this->newOptions[$propertyId] = '';
                    return;
                }
            }
            
            // Add status with predefined RUMIKU palette color
            $colors = ['#f8f149', '#49f8f1', '#49f8b4', '#fb8c89', '#D0F849'];
            $options[] = [
                'label' => $newVal,
                'color' => $colors[count($options) % count($colors)],
            ];
            $property->update(['options' => $options]);
            $this->propertyValues[$propertyId] = $newVal;
        } else {
            // Multi_select & select
            if (!in_array($newVal, $options)) {
                $options[] = $newVal;
                $property->update(['options' => $options]);
            }
            
            if ($property->type === 'multi_select') {
                if (!isset($this->propertyValues[$propertyId])) $this->propertyValues[$propertyId] = [];
                if (!in_array($newVal, $this->propertyValues[$propertyId])) {
                    $this->propertyValues[$propertyId][] = $newVal;
                }
            } else {
                $this->propertyValues[$propertyId] = $newVal;
            }
        }
        
        $this->newOptions[$propertyId] = '';
    }

    public function deleteOption($propertyId, $optionLabel)
    {
        $property = Property::find($propertyId);
        if (!$property) return;

        $options = $property->options ?? [];
        if ($property->type === 'status') {
            $options = array_values(array_filter($options, fn($opt) => 
                (is_array($opt) ? ($opt['label'] ?? '') : $opt) !== $optionLabel
            ));
        } else {
            $options = array_values(array_filter($options, fn($opt) => $opt !== $optionLabel));
        }

        $property->update(['options' => $options]);
        
        // If the current selected value was the deleted option, clear it
        if ($property->type === 'multi_select' || $property->type === 'person') {
            if (isset($this->propertyValues[$propertyId]) && is_array($this->propertyValues[$propertyId])) {
                $this->propertyValues[$propertyId] = array_values(array_filter($this->propertyValues[$propertyId], fn($v) => $v !== $optionLabel));
            }
        } elseif (($this->propertyValues[$propertyId] ?? '') === $optionLabel) {
            $this->propertyValues[$propertyId] = '';
        }

        $this->dispatch('taskSaved', 'Option deleted.');
    }

    public function addProperty()
    {
        $this->validate([
            'newPropertyName' => 'required|string|max:255',
            'newPropertyType' => 'required|in:' . implode(',', array_keys(Property::supportedTypes())),
        ]);

        $options = null;
        if (in_array($this->newPropertyType, ['select', 'multi_select'])) {
            $options = array_map('trim', explode(',', $this->newPropertyOptions));
            $options = array_filter($options);
        } elseif ($this->newPropertyType === 'status') {
            $colors = ['#f8f149', '#49f8f1', '#49f8b4', '#fb8c89', '#D0F849'];
            $labels = array_map('trim', explode(',', $this->newPropertyOptions));
            $labels = array_filter($labels);
            $options = [];
            foreach ($labels as $i => $label) {
                $options[] = [
                    'label' => $label,
                    'color' => $colors[$i % count($colors)],
                ];
            }
        }

        $maxSort = Property::max('sort_order') ?? 0;

        $property = Property::create([
            'name' => $this->newPropertyName,
            'type' => $this->newPropertyType,
            'options' => $options,
            'icon' => $this->getDefaultIconForType($this->newPropertyType),
            'sort_order' => $maxSort + 1,
            'is_default' => false,
        ]);

        $this->newPropertyName = '';
        $this->newPropertyType = 'text';
        $this->newPropertyOptions = '';
        $this->showAddProperty = false;

        // Re-initialize property values
        $this->loadPropertyDefaults();

        $this->dispatch('taskSaved', 'Property added successfully.');
    }

    public function deleteProperty($propertyId)
    {
        $property = Property::find($propertyId);
        if ($property && !$property->is_default) {
            $property->delete(); // Cascade deletes task_values for this property
            unset($this->propertyValues[$propertyId]);
            $this->dispatch('taskSaved', 'Property deleted.');
        }
    }

    private function getDefaultIconForType($type)
    {
        $map = [
            'text'         => 'document-text',
            'number'       => 'hashtag',
            'select'       => 'list-bullet',
            'multi_select' => 'square-2-stack',
            'status'       => 'check-circle',
            'date'         => 'calendar',
            'person'       => 'users',
            'checkbox'     => 'check-square',
            'url'          => 'link',
        ];
        return $map[$type] ?? 'tag';
    }

    private function loadPropertyDefaults()
    {
        $properties = Property::orderBy('sort_order')->get();
        foreach ($properties as $property) {
            if (!isset($this->propertyValues[$property->id])) {
                if (in_array($property->type, ['multi_select', 'person'])) {
                    $this->propertyValues[$property->id] = [];
                } elseif ($property->type === 'checkbox') {
                    $this->propertyValues[$property->id] = '0';
                } else {
                    $this->propertyValues[$property->id] = '';
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.tasks.task-form', [
            'properties' => Property::orderBy('sort_order')->get(),
            'users' => User::all(),
            'propertyTypes' => Property::supportedTypes(),
        ]);
    }
}
