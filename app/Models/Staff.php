<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Staff extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'full_name',
        'mother_name',
        'birth_date',
        'national_id',
        'address',
        'previous_job',
        'education_level',
        'phone',
    ];
    public function mosque_staff(){
        return $this->hasMany(Staff_mosque::class,'staff_id');
      }
      public function schedule(){
        return $this->hasMany(schedules::class,'staff_id');
      }
      public function mosques()
{
    return $this->belongsToMany(\App\Models\Mosque::class, 'staff_mosques')
                ->withPivot('role')
                ->withTimestamps()
                ->withTrashed(); 
}

}
