<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationsHeader extends Model
{
    //
    use SoftDeletes;
    protected $table = 'quotations_header';
    protected $fillable = [
        'quote_number', 'order_id','customer_id','is_approved','total_value','remarks','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
