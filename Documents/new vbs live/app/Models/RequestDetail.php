<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    use HasFactory;

    protected $table = 'request_details';

    protected $fillable = [
        'request_header_id',
        'departure_time',
        'requested_hrs',
        'destination_to',
        'destination_from',
        'passengers',
        'created_at',
        'updated_at',
        'is_removed',
        'trip_type',
        'end_time',
        'vehicle_type'
    ];

    public function details_in_range($header_ids, $start_date, $end_date) {
        $details = RequestDetail::where('request_header_id', $header_ids)
                                ->whereBetween('departure_time', [$start_date, $end_date])
                                ->get();

        foreach($details as $detail) {

        }
    
    }

    public function header(){
        return $this->belongsTo('App\RequestHeader', 'request_header_id');
    }
}
