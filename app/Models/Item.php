<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $table = 'items';
    protected $fillable = [
        'item_code','item_name', 'item_description','category_code','unit_price','is_active','division','parent_item','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
