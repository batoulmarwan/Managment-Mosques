<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedules extends Model
{
    use HasFactory;
    protected $fillable = [
        'mosque_id',
        'name',
        'type',
        'start_time',
        'end_time',
        'staff_id',
        'daysOfWeek'
    ];
    public function Mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
    public function Staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function courses()
{
    return $this->hasMany(Course::class);
}
public function memorization_sessions()
{
    return $this->hasMany(MemorizationSession::class);
}
}
