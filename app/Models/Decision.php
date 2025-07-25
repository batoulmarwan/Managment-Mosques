<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_decision',
        'full_name',
        'mosque_name',
        'date_decision',
    ];
}
