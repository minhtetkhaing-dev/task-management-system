<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    protected static function boot(){
        parent::boot();
    
        static::deleted(function($project)
        {
            foreach($project->tasks as $task){
                $task->delete();
            }
            foreach($project->timesheets as $timesheet){
                $timesheet->delete();
            }
        });
    }

}
