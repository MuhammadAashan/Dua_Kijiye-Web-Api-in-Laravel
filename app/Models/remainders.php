<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class remainders extends Model
{
    use HasFactory;
    protected $table='remainders';
    protected $fillable = [
        'user_id',
        'dua_id',
        'category',
        'remainder',
        // Add any additional columns for your "remainders" table
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
