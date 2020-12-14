<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadFollowups extends Model
{
    //
    use SoftDeletes;
    protected $table = 'lead_followups';
    protected $fillable = [
        'followed_by','contact_methode','remarks','next_schedule','created_at','updated_at','deleted_at'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
