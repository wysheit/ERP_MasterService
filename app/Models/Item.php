<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $table = 'items';
    protected $fillable = [
        'item_code','item_name', 'item_discription','category_id','is_active','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
