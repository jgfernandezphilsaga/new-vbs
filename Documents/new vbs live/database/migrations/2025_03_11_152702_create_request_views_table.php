<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("CREATE VIEW v_requests AS
                        SELECT rh.id AS header_id, 
                                rh.requesting_dept AS department, 
                                rh.requested_vehicle AS vehicle, 
                                rh.purpose, 
                                s.status, 
                                rd.departure_time, 
                                rd.requested_hrs, 
                                rd.destination_from, 
                                rd.destination_to, 
                                rd.passengers, 
                                rd.trip_type, 
                                rh.user_id AS [user], 
                                rh.dept_approver_id AS dept_approver, 
                                rh.gsd_dispatcher_id AS gsd_dispatcher, 
                                rh.created_at
                        FROM dbo.request_headers AS rh
                        INNER JOIN dbo.request_details AS rd 
                        ON rh.id = rd.request_header_id 
                        INNER JOIN dbo.statuses AS s 
                        ON rh.status = s.id
                        WHERE rd.is_removed = 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS v_requests');
    } 
};
