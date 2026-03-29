<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Task;
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

        // 3. Create 20 Random Tasks assigned to the 3 internal members
        Task::factory(20)->recycle($users)->create();
    }
}
