<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\RequestController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'api'], function() {
    Route::patch('/update-status', [RequestController::class, 'api_update_status']);
});

Route::post('/run-minute-job', function () {
    try {
        $affected = DB::connection('sqlsrv')
            ->table('vbs_db.dbo.request_details')
            ->whereRaw("CONVERT(char(16), departure_time, 120) = CONVERT(char(16), GETDATE(), 120)")
            ->update([
                'status_dept'     => 'departed',
                'updated_at' => now(),
            ]);

      DB::connection('sqlsrv')->statement("UPDATE d SET d.status = '1010', d.updated_at = GETDATE()
    FROM request_headers AS d
    INNER JOIN request_details AS h
    ON d.id = h.request_header_id
    WHERE h.status_dept = 'departed'");    

        return response()->json([
            'message'      => 'Update completed',
            'rows_updated' => $affected,
        ]);
    } catch (\Throwable $e) {
        Log::error('run-minute-job error', ['exception' => $e]);

        return response()->json([
            'error'   => 'Server error',
            'message' => $e->getMessage(),
        ], 500);
    }
});