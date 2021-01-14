<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['categoryName', 'locationName'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeParentCategory()
    {
        return $this->category->parentCategory();
    }

    public function getCategoryNameAttribute()
    {
        return $this->category()->value('name');
    }

    public function getParentCategoryNameAttribute()
    {
        return $this->parentCategory()->value('name');
    }

    public function getLocationNameAttribute()
    {
        return $this->location()->value('name');
    }
}