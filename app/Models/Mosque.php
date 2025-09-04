<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Mosque extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'city_id',
        'name',
        'address',
        'area',
        'details',
        'technical_status',
        'category',
        'has_female_section',
        'image_path',
        'deleted_at'
    ];

    // علاقة المسجد مع المدينة
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function mosque_staff(){
        return $this->hasMany(Staff_mosque::class,'mosque_id');
      }
      public function schedule(){
        return $this->hasMany(schedules::class,'mosque_id');
      }
      public function staff()
{
    return $this->belongsToMany(Staff::class, 'staff_mosques')
                ->withPivot('role')
                ->withTimestamps();
}

}
