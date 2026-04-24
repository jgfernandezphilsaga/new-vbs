<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestHeader extends Model
{
    use HasFactory;

    protected $table = 'request_headers';

    protected $fillable = [
        'requesting_dept',
        'requested_vehicle',
        'request_details_id',
        'purpose',
        'user_id',
        'dept_approver_id',
        'gsd_dispatcher_id',
        'status',
        'ticket_id',
        'created_at',
        'updated_at',
        'reference_id',
        'user_fullname',
        'dept_approver_fullname',
        'gsd_manager_fullname',
        'is_resubmitted',
        'is_confidential',
        'is_emergency',
        'is_nightdrive'
    ];

    public function details(){
        return $this->hasMany('App\RequestDetail', 'request_header_id');
    }

    public function status(){
        return $this->belongsTo('App\Status', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'id');
    }

    public function remarks(){
        return $this->hasMany('App\Remarks', 'request_header_id');
    }
}
