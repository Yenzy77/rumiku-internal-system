<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\Property;
use App\Models\TaskValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::create(['name' => 'Admin']);
        $memberRole = Role::create(['name' => 'Member']);

        // 2. Create 3 Core RUMIKU Team Members
        $users = collect([
            [
                'name' => 'Aldi CEO', 
                'email' => 'aldi@rumiku.com', 
                'role_id' => $adminRole->id,
            ],
            [
                'name' => 'Budi Ops', 
                'email' => 'budi@rumiku.com', 
                'role_id' => $memberRole->id,
            ],
            [
                'name' => 'Citra Dev', 
                'email' => 'citra@rumiku.com', 
                'role_id' => $memberRole->id,
            ],
        ])->map(function ($userData) {
            return User::create(array_merge($userData, [
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]));
        });

        // 3. Create Default Properties (Notion-style columns)
        $properties = collect([
            [
                'name' => 'Description',
                'slug' => 'description',
                'type' => 'text',
                'icon' => 'document-text', // Replaced 📝
                'options' => null,
                'sort_order' => 1,
                'is_default' => true,
            ],
            [
                'name' => 'Assignee',
                'slug' => 'assignee',
                'type' => 'person',
                'icon' => 'user-group', // Replaced 👤
                'options' => null,
                'sort_order' => 2,
                'is_default' => true,
            ],
            [
                'name' => 'Status',
                'slug' => 'status',
                'type' => 'status',
                'icon' => 'check-circle', // Status icon
                'options' => [
                    ['label' => 'To Do', 'color' => '#f8f149'],
                    ['label' => 'In Progress', 'color' => '#49f8f1'],
                    ['label' => 'Done', 'color' => '#49f8b4'],
                ],
                'sort_order' => 3,
                'is_default' => true,
            ],
            [
                'name' => 'Due Date',
                'slug' => 'due_date',
                'type' => 'date',
                'icon' => 'calendar', // Replaced 📅
                'options' => null,
                'sort_order' => 4,
                'is_default' => true,
            ],
            [
                'name' => 'Priority',
                'slug' => 'priority',
                'type' => 'select',
                'icon' => 'tag', // Priority icon
                'options' => ['Low', 'Medium', 'High', 'Urgent'],
                'sort_order' => 5,
                'is_default' => true,
            ],
        ])->map(function ($propData) {
            return Property::create($propData);
        });

        $descProp     = $properties->firstWhere('slug', 'description');
        $assigneeProp = $properties->firstWhere('slug', 'assignee');
        $statusProp   = $properties->firstWhere('slug', 'status');
        $dueDateProp  = $properties->firstWhere('slug', 'due_date');
        $priorityProp = $properties->firstWhere('slug', 'priority');

        // 4. Create 20 Random Tasks with dynamic values
        $statusLabels = ['To Do', 'In Progress', 'Done'];
        $priorities = ['Low', 'Medium', 'High', 'Urgent'];

        $tasks = Task::factory(20)->recycle($users)->create();

        foreach ($tasks as $task) {
            // Description
            TaskValue::create([
                'task_id' => $task->id,
                'property_id' => $descProp->id,
                'value' => fake()->paragraph(),
            ]);

            // Assignee (random 1 to 2 users as JSON array)
            $randomUsers = $users->random(rand(1, 2))->pluck('id')->toArray();
            TaskValue::create([
                'task_id' => $task->id,
                'property_id' => $assigneeProp->id,
                'value' => json_encode($randomUsers), // Stored as JSON array for multiple selection
            ]);

            // Status
            TaskValue::create([
                'task_id' => $task->id,
                'property_id' => $statusProp->id,
                'value' => fake()->randomElement($statusLabels),
            ]);

            // Due Date
            TaskValue::create([
                'task_id' => $task->id,
                'property_id' => $dueDateProp->id,
                'value' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            ]);

            // Priority
            TaskValue::create([
                'task_id' => $task->id,
                'property_id' => $priorityProp->id,
                'value' => fake()->randomElement($priorities),
            ]);
        }
    }
}
