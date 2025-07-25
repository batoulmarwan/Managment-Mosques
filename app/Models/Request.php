<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $table = 'requests';

    protected $fillable = ['user_id', 'type', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
