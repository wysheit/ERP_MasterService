<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $table = 'employees';
    protected $fillable = [
        'employee_code','first_name','last_name', 'nic','phone','mobile','address_line_1',
        'address_line_2','epf_number','etf_number','is_active','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];

}
