<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use SoftDeletes;
    protected $table = 'employees';
    protected $fillable = [
        'employee_code',
        'designation',
        'first_name',
        'last_name', 
        'nic',
        'telephone_1',
        'telephone_2',
        'fax',
        'address_line_1',
        'address_line_2',
        'city',
        'zip_code',
        'epf_number',
        'etf_number',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];

}
