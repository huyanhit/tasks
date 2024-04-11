<?php

namespace App\Http\Requests;

use App\Models\TaskUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MoveTaskRequest extends FormRequest
{
    const ROLE_ADMIN = 1;
    const ROLE_MEMBER = 2;
    const ROLE_VISITOR = 3;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = request()->route('task');
        $role = TaskUser::where(['user_id'=>Auth::id(), 'task_id'=>$task->id])->first();
        return match ($role->role_id) {
            self::ROLE_ADMIN => true,
            self::ROLE_MEMBER => true,
            self::ROLE_VISITOR => false,
        };
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_id' => ['required'],
        ];
    }
}
