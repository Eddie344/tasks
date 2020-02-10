<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            [
                'title' => 'Task 1',
                'description' => 'Task 1 Descr',
                'user_id' => '1',
                'status_id' => '1',
            ],
            [
                'title' => 'Task 2',
                'description' => 'Task 2 Descr',
                'user_id' => '1',
                'status_id' => '2',
            ],
            [
                'title' => 'Task 3',
                'description' => 'Task 3 Descr',
                'user_id' => '1',
                'status_id' => '3',
            ],
            [
                'title' => 'Task 4',
                'description' => 'Task 4 Descr',
                'user_id' => '1',
                'status_id' => '1',
            ],
        ]);
    }
}
