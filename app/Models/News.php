<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'image',
        'slug',
        'content'
    ];

    // function realtionship with category
    public function category() {
        // one to many relationship using belongsTo
        return $this->belongsTo(Category::class);
    }

    // Accessor Image News
    public function image() : Attribute {
        return Attribute::make(
            get: fn($value) => asset('/storage/category/' . $value)
        );
    }
}
