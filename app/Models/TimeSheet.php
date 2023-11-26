<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    use HasFactory;

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'emp_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'prj_id');
    }
}
