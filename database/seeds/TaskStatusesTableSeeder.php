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
                'title' => 'To do',
                'color' => 'danger'
            ],
            [
                'title' => 'Doing',
                'color' => 'warning'
            ],
            [
                'title' => 'Done',
                'color' => 'success'
            ]
        ]);
    }
}
