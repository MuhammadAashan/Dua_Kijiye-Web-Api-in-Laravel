<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class duausercounter extends Model
{
    use HasFactory;

    protected $table="dua_user_counters";
    protected $fillable = ['dua_id', 'user_id', 'count'];

    public function dua()
    {
        return $this->belongsTo(Dua::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
