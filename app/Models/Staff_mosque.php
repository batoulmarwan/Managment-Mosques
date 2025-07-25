<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Staff_mosque extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'mosque_id',
        'staff_id',
        'role',
    ];
    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
