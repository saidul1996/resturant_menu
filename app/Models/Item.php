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

    protected $appends = ['inherited_discount'];

    public function getInheritedDiscountAttribute()
    {
        if ($this->attributes['discount'] != 0) {
            return $this->attributes['discount'];
        } elseif ($this->category && $this->category->discount != 0) {
            return $this->category->discount;
        } elseif ($this->category->parent && $this->category->parent->discount != 0) {
            return $this->category->parent->discount ?? 0;
        } elseif ($this->category->parent->parent && $this->category->parent->parent->discount != 0) {
            return $this->category->parent->parent->discount ?? 0;
        } elseif ($this->category->parent->parent->parent && $this->category->parent->parent->parent->discount != 0) {
            return $this->category->parent->parent->parent->discount ?? 0;
        }

        return 0;
    }
}
