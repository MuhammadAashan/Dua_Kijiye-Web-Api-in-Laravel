<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class favoritedua extends Model
{
    use HasFactory;
    protected $table = 'favoritedua';

    protected $fillable = [
        'user_id',
        'dua_id',
    ];

    // Define the relationships with the User and Dua models
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dua()
    {
        return $this->belongsTo(dua::class);
    }
}
