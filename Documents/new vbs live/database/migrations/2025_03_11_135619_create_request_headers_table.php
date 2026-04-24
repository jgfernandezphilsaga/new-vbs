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
        Schema::create('request_headers', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id');
            $table->string('requesting_dept');
            $table->string('requested_vehicle');
            $table->text('purpose');
            $table->integer('user_id');//constrained to user
            $table->string('user_fullname');
            $table->integer('dept_approver_id');//constrained to user
            $table->string('dept_approver_fullname');
            $table->integer('gsd_dispatcher_id');//constrained to user
            $table->string('gsd_manager_fullname');
            $table->integer('status');//constrained to status
            $table->integer('is_resubmitted');
            $table->integer('ticket_id');
            $table->integer('remarks');
            $table->string('approval_stage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_headers');
    }
};
