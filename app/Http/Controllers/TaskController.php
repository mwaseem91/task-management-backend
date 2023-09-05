<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\TaskResource;
use App\Http\Requests\TaskStatusRequest;

class TaskController extends Controller
{
    public function index(FilterRequest $request)
    {
        $tasks = Task::query() 
            ->when($request->filter === 'completed', fn($q) => $q->where('completed', true))
            ->when($request->filter === 'incomplete', fn($q) => $q->where('completed', false))
            ->get();

        $tasks = TaskResource::collection($tasks);
        
        return response()->json([
            'status' => true,
            'data' => $tasks, 
        ]);
    }

    public function store(StoreRequest $request)
    {
        $task = Task::findOrNew($request->id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->completed = $request->completed;
        $task->save();

        $task = new TaskResource($task);
       
        return response()->json([
            'status' => true,
            'message'=> 'Saved successfully',
            'data' => $task, 
        ]);

    }


    public function completed(TaskStatusRequest $request)
    {
        $task = Task::find($request->id);
        $task->completed = !$task->completed;
        $task->save();

        $task = new TaskResource($task);

        return response()->json([
            'status' => true,
            'message'=> 'Updated successfully',
            'data' => $task, 
        ]);
    }

}
