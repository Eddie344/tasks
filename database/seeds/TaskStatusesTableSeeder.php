<?php

use Illuminate\Database\Seeder;

class TaskStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_statuses')->insert([
            [
                'title' => 'To do'
            ],
            [
                'title' => 'Doing'
            ],
            [
                'title' => 'Done'
            ]
        ]);
    }
}
