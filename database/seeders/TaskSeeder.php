<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::insert([
            [
                'title' => 'VUE TODO',
                'description' => 'Perform vue crud (create, Edit, Update, Delete)',
                'completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'LARAVEL TODO',
                'description' => 'Perform laravel crud (create, Edit, Update, Delete)',
                'completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
