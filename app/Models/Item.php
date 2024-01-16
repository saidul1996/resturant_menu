<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'price', 'discount'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
