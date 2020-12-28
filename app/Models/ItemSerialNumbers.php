<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemSerialNumbers extends Model
{
    //
    use SoftDeletes;
    protected $table = 'item_serial_numbers';
    protected $fillable = [
        'item_id','serial_no','is_active','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
