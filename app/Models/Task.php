<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status_id',
        'project_id',
        'auth_id',
        'content',
        'date_start',
        'date_end',
        'comment',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'project',
        'auth',
        'members',
        'pivot'
    ];

    protected $casts = [
        'date_start' => 'datetime:Y-m-d H:i:s',
        'date_end' => 'datetime:Y-m-d H:i:s',
    ];
    protected $appends = ['index', 'project_name', 'auth_info', 'members_info'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function auth(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }


    public function getIndexAttribute()
    {
        return $this->pivot?->index;
    }

    public function getProjectNameAttribute()
    {
        return $this->project->title;
    }

    public function getAuthInfoAttribute()
    {
        return [
            'name'=> $this->auth->name,
            'avatar'=> $this->auth->avatar
        ];
    }
    public function getMembersInfoAttribute()
    {
        return $this->members->map(function ($member){
            return [
                'id'=>$member->id,
                'name'=>$member->name,
                'avatar'=>$member->avatar,
            ];
        });
    }
}
