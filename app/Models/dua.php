<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dua extends Model
{
    use HasFactory;
    protected $table = 'dua';
    protected $casts = [
        'dua_name' => 'array', // Casts the 'multi_values' attribute to an array
    ];
    protected $fillable = [
        'user_id',
        'category_id',
        'dua_name',
        'audiolink',
        'urdu_translation',
        'english_translation',
        'arabic_translation',
        'transliteration',
    ];

    public function user()
    {
        return $this->morphTo();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function remainders()
    {
        return $this->hasMany(Remainders::class);
    }
    public function favoriteduas()
    {
        return $this->hasMany(favoritedua::class);
    }

}
