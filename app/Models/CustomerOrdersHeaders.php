<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOrdersHeaders extends Model
{
     //
     use SoftDeletes;
     protected $table = 'customer_orders_headers';
     protected $fillable = [
         'order_code','customer_id','is_quored','order_department','created_at','updated_at','deleted_at'
     ];
     public $timestamps = true;
     protected $dates = ['deleted_at'];

     public function cusOrdesDetails()
     {
         return $this->hasMany(CustomerOrderDetails::class,'order_id','id');
     }
}
