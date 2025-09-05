<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorizationSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_id',
        'name_memorization_sessions',
        'teacher_name',
        'NumberStudent',
        'Catogry',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedules::class);
    }
}
