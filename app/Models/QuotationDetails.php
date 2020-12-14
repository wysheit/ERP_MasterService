<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationDetails extends Model
{
    //
    use SoftDeletes;
    protected $table = 'quotation_details';
    protected $fillable = [
        'quotation_id', 'item','unit_price','qty','discount','amount','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
