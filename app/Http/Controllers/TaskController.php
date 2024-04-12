<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveTaskRequest;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\TaskUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    const NOT_START = 1;
    const ROLE_ADMIN = 1;
    const ROLE_MEMBER = 2;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->responseSuccess(User::find(Auth::id())->tasks);
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
        DB::beginTransaction();
        try {
            $task = Task::create([
                'title'      => $request->title,
                'project_id' => $request->project,
                'status_id'  => self::NOT_START,
                'auth_id'    => Auth::id(),
                'content'    => $request->input('content'),
                'date_start' => Carbon::parse($request->date_start)->format('Y-m-d H:i:s'),
                'date_end'   => Carbon::parse($request->date_end)->format('Y-m-d H:i:s'),
            ]);

            TaskUser::create([
                'task_id' => $task->id,
                'role_id' => self::ROLE_ADMIN,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->assign as $user){
                TaskUser::create([
                    'task_id' => $task->id,
                    'role_id' => $user['role_id'],
                    'user_id' => $user['user_id'],
                ]);
            }

            DB::commit();
            return $this->responseSuccess($task);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->responseError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $this->responseSuccess($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return $this->responseSuccess($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function move(MoveTaskRequest $request, Task $task)
    {
        $task->status_id = $request->status_id;
        $task->save();
        foreach($request->list_index as $key => $val){
            TaskUser::where('task_id', $val)->where('user_id', Auth::id())->update(['index' => $key]);
        }

        return $this->responseSuccess($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        DB::beginTransaction();
        try {
            $task->title = $request->title;
            $task->project_id = $request->project;
            $task->content = $request->input('content');
            $task->comment = $request->comment;
            $task->date_start = Carbon::parse($request->date_start)->format('Y-m-d H:i:s');
            $task->date_end = Carbon::parse($request->date_end)->format('Y-m-d H:i:s');
            $task->save();

            foreach ($task->members as $user) {
                TaskUser::where([
                    'user_id' => $user['id'],
                    'task_id' => $task->id
                ])->where('user_id', '!=', Auth::id())->delete();
            }
            foreach ($request->assign as $user){
                TaskUser::create([
                    'task_id' => $task->id,
                    'role_id' => $user['role_id'],
                    'user_id' => $user['user_id'],
                ]);
            }
            DB::commit();
            return $this->responseSuccess(Task::find($task->id));
        }catch (\Exception $e){
            DB::rollBack();
            return $this->responseError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $role = TaskUser::where(['user_id'=>Auth::id(), 'task_id'=>$task->id])->first();
        if($role->role_id === self::ROLE_ADMIN) {
            return $this->responseSuccess($task->delete());
        }else{
            return $this->responseError('not permission');
        }
    }
}
