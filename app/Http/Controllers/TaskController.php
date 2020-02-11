<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Builder[]|Collection
     */
    public function index()
    {
        return Task::with('comments.user')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $task = Task::create($request->task);
        return response()->json($task->load('comments.user'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return void
     */
    public function update(Request $request, Task $task)
    {
        $task->update($request->task);
        return response()->json($task->load('comments.user'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return Response
     * @throws Exception
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}
