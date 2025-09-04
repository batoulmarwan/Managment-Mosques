<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_id',
        'title',
        'nameTeacher',
        'days',
        'description',
        'start_date',
        'end_date',
    ];
    public function schedule()
    {
        return $this->belongsTo(schedules::class);
    }
}
