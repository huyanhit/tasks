<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = User::find(Auth::id())->tasks->map(function($task){
            $task['project_name'] = $task->project->title;
            $task['auth_info'] = [
                'name'=>$task->auth->name,
                'avatar'=>$task->auth->avatar
            ];
            $task['index'] = $task->pivot->index;
            $task['members_info'] = $task->members->map(function ($member){
                return [
                    'name'=>$member->name,
                    'avatar'=>$member->avatar
                ];
            });

            unset($task->members);
            unset($task->project);
            unset($task->auth);
            unset($task->pivot);

            return $task;
        });

        return $this->responseSuccess($task);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->status_id = $request->status;
        $task->save();
        foreach($request->indexs as $key => $val){
            TaskUser::where('task_id', $val)->where('user_id', Auth::id())->update(['index' => $key]);
        }

        return $this->responseSuccess($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
