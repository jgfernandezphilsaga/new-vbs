<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    use HasFactory;

    protected $table = 'remarks';

    protected $fillable = [
        'request_header_id',
        'remarks',
        'sender_name',
        'sender_position',
        'is_read',
        'created_at',
        'updated_at',
        'status'
    ];
    
    public function header(){
        return $this->belongsTo('App\RequestHeader', 'id');
    }
}
