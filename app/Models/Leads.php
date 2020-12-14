<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model
{
    use SoftDeletes;
    protected $table = 'leads';
    protected $fillable = [
        'company_name','contact_person', 'telephone','email','address','handled_by','lead_type',
        'next_schedule','remarks','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
