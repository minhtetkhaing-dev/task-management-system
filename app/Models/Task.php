<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    protected static function boot(){
        parent::boot();
    
        static::deleted(function($task)
        {
            foreach($task->timesheets as $timesheet){
                $timesheet->delete();
            }
        });
    }
}
