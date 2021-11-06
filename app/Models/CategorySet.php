<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySet extends Model
{
    use HasFactory;

    public $table = 'categorysets';

    public function category()
    {
        return $this->belongsToMany('App\Models\Category', 'category_set_link', 'categoryset_id', 'category_id')->withPivot('shop');
    }
}
