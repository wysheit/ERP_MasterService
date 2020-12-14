<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CustomerOrderDetails extends Model
{
     //
     use SoftDeletes;
     protected $table = 'customer_order_details';
     protected $fillable = [
         'order_id','item','qty','created_at','updated_at','deleted_at'
     ];
     public $timestamps = true;
     protected $dates = ['deleted_at'];
     
     public function CustomerOrdersHeaders()
     {
         return $this->belongsTo(CustomerOrdersHeaders::class);
     }
}
