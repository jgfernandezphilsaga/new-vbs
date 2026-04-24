<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_details', function (Blueprint $table) {
            $table->id();
            $table->integer('request_header_id'); //should be constrained to requestheader
            $table->datetime('departure_time');             
            $table->integer('requested_hrs');             
            $table->string('destination_from');             
            $table->string('destination_to');             
            $table->text('passengers');            
            $table->timestamps();
            $table->integer('is_removed');
            $table->string('trip_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_details');
    }
};
