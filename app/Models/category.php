<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $casts = [
        'name' => 'array', // Casts the 'multi_values' attribute to an array
    ];
    protected $fillable = [
        'name',
        // Add any additional columns for your "category" table
    ];
    public function duas()
    {
        return $this->hasMany(Dua::class);
    }
}
