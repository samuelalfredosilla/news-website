<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk slug

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Mutator untuk otomatis membuat slug saat nama di set
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function newsArticles()
    {
        return $this->hasMany(NewsArticle::class);
    }
}
