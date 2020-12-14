<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategories extends Model
{
    //
    use SoftDeletes;
    protected $table = 'item_categories';
    protected $fillable = [
        'category_code','category_name','is_active','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
