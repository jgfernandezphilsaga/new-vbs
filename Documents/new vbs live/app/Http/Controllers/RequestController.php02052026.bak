<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Remark;
use App\Models\RequestDetail;
use App\Models\RequestHeader;
use App\Models\Status;
use App\Models\User;
use App\Models\VehicleType;


use App\Models\RequestView;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function get_dashboard(){

    $userId = session('user_id');
    $query = RequestHeader::query()
        ->join('users', 'users.id', '=', 'request_headers.user_id')
        ->select('request_headers.*', 'users.full_name')
        ->where(function ($subQ) use ($userId) {
            $subQ->where(function ($q1) use ($userId) {
                $q1->where('users.id', $userId)
                    ->where('status', '!=', 6);
            })
            ->orWhere(function ($q2) use ($userId) {
                $q2->where('users.id', '!=', $userId)
                    ->where('status', 1009);
            });
        });


$summary = DB::table(DB::raw("({$query->toSql()}) as sub"))
    ->mergeBindings($query->getQuery()) // 👈 this is important for bindings
    ->selectRaw("
        SUM(CASE WHEN status = 1009 THEN 1 ELSE 0 END) AS for_approval_count,
        SUM(CASE WHEN status = 1012 THEN 1 ELSE 0 END) AS draft,
        SUM(CASE WHEN status = 1010 THEN 1 ELSE 0 END) AS completed_count,
        SUM(CASE WHEN status = 1011 THEN 1 ELSE 0 END) AS posted_count,
        SUM(CASE WHEN status = 1013 THEN 1 ELSE 0 END) AS disapproved_count,
        SUM(CASE WHEN status = 5    THEN 1 ELSE 0 END) AS hold_count
    ")
    ->first();

        $request_headers = $query->get();

    $data = $request_headers->map(function ($header) {
    $statusHtml = match ((int)$header->status) {
        1012 => '<span class="badge status-draft m-0 w-100">DRAFT</span>',
        3    => '<span class="badge status-approved m-0 w-100">APPROVED</span>',
        5    => '<span class="badge status-hold m-0 w-100">HOLD</span>',
        6    => '<span class="badge status-disapproved m-0 w-100">DISAPPROVED</span>',
        1008 => '<span class="badge status-fully-approved m-0 w-100">FULLY APPROVED</span>',
        1006 => '<span class="badge status-in-progress m-0 w-100">IN-PROGRESS</span>',
        1007 => '<span class="badge status-pending m-0 w-100">PENDING</span>',
        1009 => '<span class="badge status-approved-open m-0 w-100">APRVD(OPEN)</span>',
        1010 => '<span class="badge status-fully-approved m-0 w-100">COMPLETED</span>',
        1011 => '<span class="badge status-pending m-0 w-100">PENDING</span>',
        1013 => '<span class="badge status-disapproved m-0 w-100">CANCEL</span>',
        default => '<span class="badge m-0 w-100">UNKNOWN</span>',
    };

    $editButton = in_array($header->status, [1012, 5, 1009])
        ? '<a class="btn btn-warning" href="' . route('edit.request', ['id' => $header->id]) . '" data-bs-toggle="tooltip" title="Edit this item">
                <i class="fa-solid fa-pen-to-square" style="color:white"></i>
            </a>'
        : '<a class="btn" style="opacity: 0; cursor: default">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>';

    $actionsHtml = '
        <a class="btn btn-secondary" data-bs-toggle="modal" title="View" data-bs-target="#requestDetailModal" data-request-id="' . $header->id . '">
            <i class="fa-solid fa-eye"></i>
        </a>
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestDetailModal" data-print="true" title="Print" data-request-id="' . $header->id . '">
            <i class="fa-solid fa-print"></i>
        </a>
        ' . $editButton;

    return [
        'reference_no' =>$header->reference_id,
        'id'=>$header->id,
        'requesting_dept' => $header->requesting_dept,
        'user_fullname'   => $header->user_fullname,
        'purpose'  => $header->purpose,
        'status_html'          => $statusHtml,
        'created_at'=> $header->created_at->format('Y-m-d H:i:s'),
        'actions'         => $actionsHtml,
        'status'         => $header->status
    ];

});

return response()->json([
    'data'    => $data,
    'summary' => $summary,
]);


}
public function getTodayRequests()
{
    $today = now()->toDateString();

    $query = DB::connection('sqlsrv')->table(DB::raw("(
            SELECT 
                b.id,
                MAX(b.request_reference) AS reference_no,
                MAX(a.department) AS department,
                MAX(a.user_fullname) AS user_fullname,
                MAX(a.departure_time) AS departure_time,
                MAX(a.destination_to) AS destination_to,
                MAX(a.trip_type) AS trip_type,
                MAX(a.status) AS status
            FROM v_requests AS a
            LEFT JOIN (
                SELECT 
                    id,
                    header_id,
                    dispatch_reference,
                    request_reference,
                    value AS split_id
                FROM dispatch_table
                CROSS APPLY STRING_SPLIT(header_id, ',')
            ) AS b
                ON a.header_id = TRY_CAST(b.split_id AS INT)
            WHERE b.dispatch_reference IS NOT NULL
            AND a.departure_time BETWEEN '{$today} 00:00:00' AND '{$today} 23:59:59'
            GROUP BY b.id
        ) as t"))
        ->select('t.*');

    $request_headers = $query->get();

    $data = $request_headers->map(function ($header) {
    $statusHtml = match ((int)$header->status) {
        1012 => '<span class="badge status-draft m-0 w-100">DRAFT</span>',
        3    => '<span class="badge status-approved m-0 w-100">APPROVED</span>',
        5    => '<span class="badge status-hold m-0 w-100">HOLD</span>',
        6    => '<span class="badge status-disapproved m-0 w-100">DISAPPROVED</span>',
        1008 => '<span class="badge status-fully-approved m-0 w-100">FULLY APPROVED</span>',
        1006 => '<span class="badge status-in-progress m-0 w-100">IN-PROGRESS</span>',
        1007 => '<span class="badge status-pending m-0 w-100">PENDING</span>',
        1009 => '<span class="badge status-approved-open m-0 w-100">APRVD(OPEN)</span>',
        1010 => '<span class="badge status-fully-approved m-0 w-100">COMPLETED</span>',
        1011 => '<span class="badge status-pending m-0 w-100">PENDING</span>',
        1013 => '<span class="badge status-disapproved m-0 w-100">CANCEL</span>',
        default => '<span class="badge m-0 w-100">UNKNOWN</span>',
    };

   '<a class="btn" style="opacity: 0; cursor: default">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>';

    $actionsHtml = '
        <a class="btn btn-secondary" data-bs-toggle="modal" title="View" data-bs-target="#requestDetailModal" data-request-id="' . $header->id . '">
            <i class="fa-solid fa-eye"></i>
        </a>
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestDetailModal" data-print="true" title="Print" data-request-id="' . $header->id . '">
            <i class="fa-solid fa-print"></i>
        </a>
        ';

    return [
        'reference_no' =>$header->reference_no,
        'requesting_dept' => $header->department,
        'user_fullname'   => $header->user_fullname,
        'departure_time'  => $header->departure_time,
        'destination_to'  => $header->destination_to,
        'trip_type'       => $header->trip_type,
        'status_html'          => $statusHtml,
        'actions'         => $actionsHtml,
        'status'         => $header->status
    ];
});

return response()->json(['data' => $data]);

}

    public function save_dispatch_details(Request $request)
{
    $passengers      = $request->passengers ?? [];
    $driver_details  = $request->driver_details ?? [];
    $vehicle_details = $request->vehicle_unit ?? [];
    $detail_id       = $request->detail_ids ?? [];
    $headers_id       = $request->header_ids ?? [];
    $reference_no   = $request->reference_no ?? [];
    $requestor_name = $request->requestor_name ?? [];
    $requestor_dept = $request->requestor_dept ?? [];
    $purpose = $request->purpose;
    $orig_purpose = $request->orig_purpose ?? [];

    $validator = Validator::make($request->all(), [
        'driver_details' => 'required|array|min:1',
        'vehicle_unit'   => 'required|array|min:1',
        'passengers'     => 'required|array|min:1',
    ], [
        'driver_details.required' => 'Please select at least one driver.',
        'vehicle_unit.required'   => 'Please select at least one vehicle.',
        'passengers.required'     => 'Please add at least one passenger.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'     => 'error',
            'message'    => 'Please fill in all required fields.',
            'input'      => $validator->errors()->keys(),
        ], 422);
    }

        $new_request_id = DB::connection('sqlsrv')
        ->table('dispatch_table')
        ->insertGetId([
            'header_id'          => implode(',', $headers_id),
            'details_id'         => implode(',', $detail_id),
            'drivers_details'    => implode(',', $driver_details),
            'vehicle_details'    => implode(',', $vehicle_details),
            'passengers'         => implode(',', $passengers),
            'request_reference'  => implode(',', $reference_no),
            'requestor_name'     => implode(',', $requestor_name),
            'requestor_dept'     => implode(',', $requestor_dept),
            'purpose'            => $purpose,
            'origin_purpose'     => implode(',', $orig_purpose),
            'status'            => 'PENDING',
            'created_at'            => Carbon::createFromFormat('Y-m-d\TH:i', Carbon::now()->format('Y-m-d\TH:i'))
        ]);

    DB::connection('sqlsrv')
        ->table('dispatch_table')
        ->where('id', $new_request_id)
        ->update([
            'dispatch_reference' => $this->dispatchTransId($new_request_id)
        ]);

    if (!empty($detail_id)) {
    DB::connection('sqlsrv')
        ->table('request_details')
        ->whereIn('id', $detail_id)
        ->update(['is_dispatched' => 1]);
    }

    if (!empty($headers_id)) {
            DB::connection('sqlsrv')
                ->table('request_headers')
                ->whereIn('id', $headers_id)
                ->update(['status' => '1011']);
        }

$this->post_dispatch_api($new_request_id);
    
    return response()->json([
        'status'  => 'success',
        'message' => 'Dispatch successfully created!',
        'route'   => route('dispatch.create'), 
    ]);
}
       


public function get_passengers_list(Request $request){

    $selectedIds = $request->input('selected_ids', []);

        if (!is_array($selectedIds)) {

            $selectedIds = explode(',', $selectedIds);
        }

        if (!empty($selectedIds)) {
            $details = DB::connection('sqlsrv')
                ->table('request_details as a')
                ->join('request_headers as b', 'a.request_header_id', '=', 'b.id')
                ->whereIn('a.id', $selectedIds) 
                ->get();
                        
            return response()->json([
                'message' => 'Selected details retrieved successfully!',
                'data' => $details
            ]);
        }

return response()->json(['message' => 'No IDs selected.'], 400);
    
}    
public function get_dispatch_details(Request $request)
{
   $query = DB::connection('sqlsrv')
    ->table('request_details as a')
    ->join('request_headers as b', 'a.request_header_id', '=', 'b.id')
    ->whereNull('a.is_dispatched')
    ->where('b.status', '=', 1007)
    ->orderBy('a.id')
    ->select(
        'b.reference_id',
        'b.requesting_dept',
        'a.destination_from',
        'a.destination_to',
        'a.end_time',
        'b.id as id_header',
        'a.id as id_details',
        'b.is_confidential',
        'b.is_emergency',
        DB::raw("(LEN(CAST(a.passengers AS VARCHAR(MAX))) - LEN(REPLACE(CAST(a.passengers AS VARCHAR(MAX)), '/', '')) + 1) as passenger_count")
    );


        if ($request->filled('date')) {
            $date = Carbon::parse(str_replace("_", " ", $request->date))->toDateString();

            $query->whereDate('a.departure_time', $date)
                ->where('a.is_removed', 0);
        }

        $request_headers = $query->orderByDesc('a.departure_time')->get();

        $data = $request_headers->map(function ($header) {
            return [
                'id_header'         => $header->id_header,
                'id_details'        => $header->id_details,
                'ref'               => $header->reference_id,
                'requesting_dept'   => $header->requesting_dept, 
                'destination_from'  => $header->destination_from,
                'destination_to'    => $header->destination_to,
                'end_time'          => $header->end_time,
                'is_confidential' => ($header->is_confidential == 1) ? 'Yes' : 'No',
                'is_emergency'    => ($header->is_emergency == 1) ? 'Yes' : 'No',
                'passenger_count'   => $header->passenger_count,
            ];
        });

        return response()->json(['data' => $data]);
  
}    

public function dispatch_create(Request $request)
{

    return view('admin.forms.create_dispatch');

}

public function filterRequests(Request $request)
{

$query = DB::connection('sqlsrv')
    ->table('v_requests as a')
    ->select([
        'b.id',
        DB::raw('MAX(b.request_reference) AS reference_no'),
        DB::raw('MAX(a.department) AS department'),
        DB::raw('MAX(a.user_fullname) AS user_fullname'),
        DB::raw('MAX(a.departure_time) AS departure_time'),
        DB::raw('MAX(a.destination_to) AS destination_to'),
        DB::raw('MAX(a.trip_type) AS trip_type'),
        DB::raw('MAX(a.status) AS status')
    ])
    ->leftJoin(DB::raw("(
            SELECT 
                id,
                header_id,
                dispatch_reference,
                request_reference,
                value AS split_id
            FROM dispatch_table
            CROSS APPLY STRING_SPLIT(header_id, ',')
        ) AS b"),
        DB::raw('a.header_id'), '=', DB::raw('TRY_CAST(b.split_id AS INT)')
    )
    ->whereNotNull('b.dispatch_reference')
    ->groupBy('b.id');


if ($request->filled('dateRange')) {
    $date_range = explode("|", $request->dateRange);
    $start_date = Carbon::parse(str_replace("_", " ", $date_range[0]))->startOfDay();
    $end_date   = Carbon::parse(str_replace("_", " ", $date_range[1]))->endOfDay();

    $query->whereBetween('a.departure_time', [$start_date, $end_date]);
} else {
    $start_date = now()->startOfDay();
    $end_date   = now()->endOfDay();

    $query->whereBetween('a.departure_time', [$start_date, $end_date]);
}


$summary = DB::connection('sqlsrv')
    ->table(DB::raw("({$query->toSql()}) as sub"))
    ->mergeBindings($query) 
    ->selectRaw("
        SUM(CASE WHEN status = '1011' THEN 1 ELSE 0 END) AS posted_count,
        SUM(CASE WHEN status = '1010' THEN 1 ELSE 0 END) AS completed_count,
        SUM(CASE WHEN status = '1013' THEN 1 ELSE 0 END) AS disapproved_count,
        SUM(CASE WHEN status = '5'    THEN 1 ELSE 0 END) AS hold_count
    ")
    ->first();


$request_headers = $query->orderByDesc(DB::raw('MAX(a.departure_time)'))->get();


$data = $request_headers->map(function ($header) {
    $statusHtml = match ((int)$header->status) {
        1012 => '<span class="badge status-draft m-0 w-100">DRAFT</span>',
        3    => '<span class="badge status-approved m-0 w-100">APPROVED</span>',
        5    => '<span class="badge status-hold m-0 w-100">HOLD</span>',
        6    => '<span class="badge status-disapproved m-0 w-100">DISAPPROVED</span>',
        1007 => '<span class="badge status-pending m-0 w-100">PENDING</span>',
        1010 => '<span class="badge status-fully-approved m-0 w-100">COMPLETED</span>',
        1011 => '<span class="badge status-pending m-0 w-100">PENDING</span>',
        1013 => '<span class="badge status-disapproved m-0 w-100">CANCEL</span>',
        default => '<span class="badge m-0 w-100">UNKNOWN</span>',
    };

    

    $actionsHtml = '
        <a class="btn btn-secondary" data-bs-toggle="modal" title="View" data-bs-target="#requestDetailModal" data-request-id="' . $header->id . '">
            <i class="fa-solid fa-eye"></i>
        </a>
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestDetailModal" data-print="true" title="Print" data-request-id="' . $header->id . '">
            <i class="fa-solid fa-print"></i>
        </a>
        ';

    return [
        'reference_no' =>$header->reference_no,
        'requesting_dept' => $header->department,
        'user_fullname'   => $header->user_fullname,
        'departure_time'  => $header->departure_time,
        'destination_to'  => $header->destination_to,
        'trip_type'       => $header->trip_type,
        'status_html'          => $statusHtml,
        'actions'         => $actionsHtml,
        'status'         => $header->status,
    ];
});

return response()->json([
    'data'    => $data,
    'summary' => $summary,
]);


    }

  public function request_vehicles()
    {

    $vehicle_types = VehicleType::all()->pluck('type');

    $results = $vehicle_types->map(function ($vehicle) {
    return [
        'id' => $vehicle,
        'text' => $vehicle,
    ];
    });

return response()->json([
    'items' => $results,
    ]);
    }

   public function get_employees(Request $request)
{
    $term = $request->term;
    $page = $request->page ?? 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $query = DB::connection('sqlsrv3')
        ->table('viewHREmpMaster')
        ->select('EmpID', 'FullName');

    if ($term) {
        $query->where('FullName', 'like', "%{$term}%");
    }

    $total_count = $query->count();

    $employees = $query->offset($offset)
                       ->limit($limit)
                       ->get();

    $results = $employees->map(function ($employee) {
        return [
            'id' => $employee->EmpID . '|' . $employee->FullName,
            'text' => $employee->FullName,
        ];
    });

    return response()->json([
        'items' => $results,
        'total_count' => $total_count
    ]);
}

    public function get_drivers(Request $request)
    {
            $term = $request->term;
            $page = $request->page ?? 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;


            $query = DB::connection('sqlsrv1')
                ->table('driver_details as t1')
                ->select('t1.employee_id', 't1.last_name', 't1.first_name');
               
            if ($term) {
                $query->where(function($q) use ($term) {
                    $q->where('t1.last_name', 'like', "%{$term}%")
                    ->orWhere('t1.employee_id', 'like', "%{$term}%");
                });
            }

            $total_count = $query->count();

            $drivers = $query->offset($offset)
                            ->limit($limit)
                            ->get();

            $results = $drivers->map(function ($driver) {
                return [
                    'id' => $driver->employee_id,
                    'text' => $driver->employee_id . ' - ' . $driver->last_name . ', ' . $driver->first_name,
                ];
            });

            return response()->json([
                'items' => $results,
                'total_count' => $total_count
            ]);
            }

    public function get_vehicles(Request $request)
    {
        $term = $request->term;
            $page = $request->page ?? 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;


            $query = DB::connection('sqlsrv1')
                ->table('vms_db.dbo.masters as t1')
                ->select('t1.MODEL', 't1.EQUIPMENT', DB::raw('[t1].[PLATE No#] as PLATE_NO'));
               

             if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('t1.LOCATION/DEPARTMENT','GSD')
                ->where('t1.PLATE No#', 'like', "%{$term}%")
                ->orWhere('t1.MODEL', 'like', "%{$term}%");
            });
        }

            $total_count = $query->count();

            $vehicles = $query->offset($offset)
                ->limit($limit)
                ->get();

            $results = $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->PLATE_NO,
                    'text' => $vehicle->PLATE_NO . ' - ' . $vehicle->MODEL,
                ];
            });

            return response()->json([
                'items' => $results,
                'total_count' => $total_count
            ]);


        }

        
   public function save_process(Request $request)
{

        $data = $request->all();

        $headerIds = is_array($data['header_id']) 
            ? $data['header_id']
            : explode(',', $data['header_id']);

        $data['header_id'] = implode(',', $headerIds); 

        $validator = Validator::make($data, [
            'header_id'       => 'required|string',
            'driver_details' => 'required|array|min:1',
            'vehicle_unit' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            $failed_inputs = array_map(fn($f) => explode('.', $f)[0], array_keys($validator->failed()));
            return response()->json([
                'status'     => 'error',
                'message'    => 'Please correct the highlighted fields.',
                'validation' => 'initial',
                'input'      => $failed_inputs
            ], 422);
        }
    
        
    $db = DB::connection("sqlsrv");

    foreach ($headerIds as $headerId) {
    $vehicleRequests = $request->input('id_vehicle_request');
    $driverDetails   = $request->input('driver_details');
    $vehicleUnits    = $request->input('vehicle_unit');

    $count = count($vehicleRequests);

    for ($i = 0; $i < $count; $i++) {
        $vehicleRequestId = $vehicleRequests[$i] ?? null;
        $drivers = $driverDetails[$i] ?? [];
        $vehicles = $vehicleUnits[$i] ?? [];

        
        if (!$vehicleRequestId || empty($drivers) || empty($vehicles)) {
            continue;
        }

        $values = [
            'vehicle_request_id' => $vehicleRequestId,
            'header_id'          => $headerId,
            'driver_details'     => implode(',', $drivers),  
            'vehicle_details'    => implode(',', $vehicles), 
            'created_at'         => now(),
            'updated_at'         => now(),
        ];

        $exists = DB::connection("sqlsrv")
            ->table("driver_vehicle_details")
            ->where('header_id', $headerId)
            ->where('vehicle_request_id', $vehicleRequestId)
            ->exists();

        if ($exists) {
            DB::connection("sqlsrv")
                ->table("driver_vehicle_details")
                ->where('header_id', $headerId)
                ->where('vehicle_request_id', $vehicleRequestId)
                ->update($values);
        } else {
            DB::connection("sqlsrv")
                ->table("driver_vehicle_details")
                ->insert($values);
        }
    }

        DB::connection("sqlsrv")
            ->table("request_headers")
            ->where('id', $headerId)
            ->update(['status' => '1011']);
    }

        $this->update_to_api($headerId);
   

     Session::flash('success', 'Successfully submitted request!');
    return response()->json([
         'status' => 'success',
         'message' => 'Successfully submitted request!',
         'route' => "/"
     ]);
        }



    public function index()
    {

        if (session('user_role') === 'dept_secretary') {
       
        $statuses = Status::where('id', '!=', 6)->get();
        $departments = RequestHeader::distinct()->pluck('requesting_dept');
        $vehicle_types = VehicleType::pluck('type');

        return view('admin.dashboard.dashboard', compact( 'statuses', 'departments', 'vehicle_types'));
   
    }



    if (session('user_role') === 'gsd_dispatcher') {
        

    
        // dd($query, $request_headers);
        return view('admin.dashboard.dispatcher_new');
        }

        if (session('user_role') === 'gsd_manager') {
         $query = RequestHeader::query()->join('users', 'users.id', '=', 'request_headers.user_id')->select('request_headers.*', 'users.full_name')->where('status', '!=', 6);

        if (isset($_GET['dept']) && (session('user_role') !== 'dept_secretary')) {
            if ($_GET['dept'] != '') {
                $dept = urldecode($_GET['dept']);
                $query = $query->where('requesting_dept', 'LIKE', '%' . $dept . '%');
            } elseif ($_GET['dept'] != 'undefined') {
                unset($_GET['dept']);
            }
        }
        
        if (isset($_GET['vehicle'])) {
            if ($_GET['vehicle'] != '') {
                $vehicle = urldecode($_GET['vehicle']);
                $query = $query->where('requested_vehicle', $vehicle);
            }
        }

        if (isset($_GET['statuses'])) {
            $query = $query->whereIn('status', explode(",", $_GET['statuses']));
        }

        if (isset($_GET['dateRange'])) {
            $date_range = explode("|",$_GET['dateRange']);

            $start_date = Carbon::parse(str_replace("_"," ",$date_range[0]));
            $end_date = Carbon::parse(str_replace("_"," ",$date_range[1]));

            $current_filtered_headers = $query->get();

            $header_ids = [];
            foreach($current_filtered_headers as $header) {
                array_push($header_ids, $header->id);
            }

            $details = RequestDetail::whereIn('request_header_id', $header_ids)->where('is_removed', 0)->get();

            foreach ($details as $detail) {

                // If the $id is not found, skip the row
                $id = $detail->request_header_id;
                if(!in_array($id, $header_ids)) {
                    continue;
                }
                
                $date = Carbon::parse($detail->departure_time);

                // Check if $detail departure_time is not in range
                if($date < $start_date || $date > $end_date) {

                    // Find the key of the ID to be deleted
                    $key = array_search($id, $header_ids);
                    unset($header_ids[$key]);
                }
            }
            
            $query = $query->whereIn('request_headers.id', $header_ids);
        }

        $request_headers = $query->orderBy('id', 'DESC')->get();
        
        if (session('user_role') === 'dept_secretary') {
            $count_collection = RequestHeader::where('status', '!=', 6)->where('user_id', session('user_id'))->get();
        } elseif (session('user_role') === 'gsd_dispatcher') {
            $count_collection = RequestHeader::where('status', '!=', 6)->whereNotNull('dept_approver_fullname')->get();
        } elseif (session('user_role') === 'gsd_manager') {
            $count_collection = RequestHeader::where('status', '!=', 6)->get();
        } else {
            $count_collection = RequestHeader::where('status', '!=', 6)->get();
        }

        $statuses = Status::where('id', '!=', 6)->get(); // Exclude DISAPPROVED status because unused
        $departments = RequestHeader::distinct()->pluck('requesting_dept');
        $vehicle_types = VehicleType::all()->pluck('type');

        $saved_count = $count_collection->where('status', '1012')->count();
        $posted_count = $count_collection->where('status', '1011')->count();
        $approved_count = $count_collection->where('status', 3)->count();
        $completed_count = $count_collection->where('status', '1010')->count();
        $hold_count = $count_collection->where('status', 5)->count();
        $disapproved_count = $count_collection->where('status', 6)->count();
        // dd($query, $request_headers);
        return view('admin.dashboard.manager', compact('request_headers', 'statuses', 'departments', 'vehicle_types', 'saved_count', 'posted_count', 'approved_count', 'completed_count', 'hold_count', 'disapproved_count'));
   
    }
        
    }

    public function create()
    {
        
        return view('admin.forms.create');
    }

    public function process(Request $request,$id)
    {
        $header = RequestHeader::where('id', $id)->first();
        $details = RequestDetail::where('request_header_id', $id)->where('is_removed', 0)->get();
       
        $vehicles_request = DB::connection('sqlsrv')
            ->table('vehicle_types_tbl')
            ->where('header_id',$id)
            ->get();
        

        return view('admin.forms.process', compact('header', 'details','vehicles_request'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        


    $user_dept = session('user_department'); 
    $user_id = session('user_id'); 
    $user_fullname = User::where('id', $user_id)->pluck('full_name')->first();
        
    $purpose               = $request->purpose;
    $requested_vehicle     = $request->vehicle_type ?? [];
    $datetime              = $request->input('start_datetime', []);
    $dateend               = $request->input('end_datetime', []);
    $requested_hrs         = $request->requested_hrs ?? [];
    $destination_from      = $request->destination_from ?? [];
    $destination_to        = $request->destination_to ?? [];
    $trip_type             = $request->trip_type ?? [];
    $is_confidential       = $request->is_confidential;
    $is_emergency          = $request->is_emergency;
    $is_nightdrive         = $request->is_nightdrive;
    $passengers            = $request->passengers ?? [[]];

    // Initial Validation
    $data = [
        'purpose'           => $purpose,
        'requested_vehicle' => $requested_vehicle,
        'datetime'          => $datetime,
        'dateend'           => $dateend,
        'requested_hrs'     => $requested_hrs,
        'destination_from'  => $destination_from,
        'destination_to'    => $destination_to,
        'trip_type'         => $trip_type,
        'passengers'        => $passengers,
    ];
    
    $validation_result = $this->request_validation('initialValidation', $data); 
    if ($validation_result['status'] == 'fail') {
        $failed_initial = [];
        foreach ($validation_result['failed_input'] as $input) {
            $failed_initial[] = explode('.', $input)[0];
        }
        return response()->json([
            'status'     => 'error', 
            'message'    => $validation_result['message'], 
            'validation' => 'initial',
            'input'      => $failed_initial
        ]);
    }

    // Find row count
    $no_of_rows = max(
        count($datetime), 
        count($dateend), 
        count($requested_hrs),
        count($destination_from), 
        count($destination_to), 
        count($trip_type), 
        count($passengers)
    );

    // Remove null rows safely
    $count = 0;
        for ($index = 0; $index < $no_of_rows; $index++) {
            if (
                empty($datetime[$index]) &&
                empty($dateend[$index]) &&
                empty($is_confidential[$index]) &&
                empty($requested_hrs[$index]) &&
                empty($destination_from[$index]) &&
                empty($destination_to[$index]) &&
                empty($trip_type[$index]) &&
                (!isset($passengers[$index][0]) || $passengers[$index][0] === 'null')
            ) {
                unset($datetime[$index], $dateend[$index],
                    $requested_hrs[$index], $destination_from[$index],
                    $destination_to[$index], $trip_type[$index], $passengers[$index]);
                $count++;
            }
        }
        $no_of_rows -= $count;

        // Reindex arrays
        $datetime         = array_values($datetime);
        $dateend          = array_values($dateend);
        $requested_hrs    = array_values($requested_hrs);
        $destination_from = array_values($destination_from);
        $destination_to   = array_values($destination_to);
        $trip_type        = array_values($trip_type);
        $passengers       = array_values($passengers);

        // Latter validation
        $data = [
            'datetime'          => $datetime,
            'dateend'           => $dateend,
            'requested_vehicle' => $requested_vehicle,
            'requested_hrs'     => $requested_hrs,
            'destination_from'  => $destination_from,
            'destination_to'    => $destination_to,
            'trip_type'         => $trip_type,
            'passengers'        => $passengers,
            'is_confidential'   => $is_confidential
        ];

        $validation_result = $this->request_validation('latterValidation', $data);
        if ($validation_result['status'] == 'fail') {
            $failed_rows   = [];
            $failed_inputs = [];
            foreach ($validation_result['failed_input'] as $input) {
                [$field, $row] = explode('.', $input);
                $failed_inputs[] = $field;
                $failed_rows[]   = $row;
            }
            return response()->json([
                'status'     => 'error', 
                'message'    => $validation_result['message'], 
                'validation' => 'latter', 
                'row'        => $failed_rows,
                'input'      => $failed_inputs
            ]);
        }

        // Create Request Header
        $new_request = RequestHeader::create([
            'requesting_dept' => $user_dept,
            'purpose'         => $purpose,
            'user_id'         => $user_id,
            'status'          => '1012',
            'user_fullname'   => $user_fullname,
            'is_confidential' => $is_confidential,
            'is_emergency'    => $is_emergency,
            'is_nightdrive'   => $is_nightdrive,
        ]);

        // Generate reference ID
        RequestHeader::where('id', $new_request->id)
            ->update(['reference_id' => $this->generateTransId($new_request->id)]);

        for ($index = 0; $index < $no_of_rows; $index++) {
            $passengers_string = implode("/", $passengers[$index] ?? []);
            RequestDetail::create([
                'request_header_id' => $new_request->id,
                'departure_time' => Carbon::parse($datetime[$index]),
                'end_time'       => Carbon::parse($dateend[$index]),
                'requested_hrs'     => $requested_hrs[$index] ?? 0,
                'destination_from'  => $destination_from[$index] ?? '',
                'destination_to'    => $destination_to[$index] ?? '',
                'passengers'        => $passengers_string, 
                'is_removed'        => 0,
                'trip_type'         => $trip_type[$index] ?? '',
                'vehicle_type' => $requested_vehicle[$index] ?? ''
            ]);
        }

        Session::flash('success', 'Successfully submitted request!');
        return response()->json(['route' => "/"]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $header = RequestHeader::where('id', $id)->first();
        $detail = RequestView::where('header_id', $id)->get();
        $remarks = Remark::where('request_header_id', $id)->get();
        // $detail = RequestView::all(); // get();

        

        $requestor = User::where('id', $header->user_id)->pluck('full_name')->first();
        $dept_approver = User::where('id', $header->dept_approver_id)->pluck('full_name')->first();
        $dispatcher = User::where('id', $header->gsd_dispatcher_id)->pluck('full_name')->first();


        $allDriversIds = DB::connection('sqlsrv')
        ->table('dispatch_table')
        ->whereRaw("',' + header_id + ',' LIKE ?", ['%,' . $id . ',%'])
        ->pluck('drivers_details')
        ->flatMap(fn($driverString) => array_map('trim', explode(',', $driverString)))
        ->unique()
        ->values()
        ->toArray();
                

        $driver_details = DB::connection('sqlsrv1')
            ->table('driver_details') 
            ->select('employee_id', 'last_name', 'first_name')
            ->whereIn('employee_id', $allDriversIds)
            ->get();


        $allVehiclePlates = DB::connection('sqlsrv')
            ->table('dispatch_table')
            ->whereRaw("',' + header_id + ',' LIKE ?", ['%,' . $id . ',%'])
            ->pluck('vehicle_details')
            ->flatMap(fn($vehicleString) => array_map('trim', explode(',', $vehicleString)))
            ->unique()
            ->values()
            ->toArray();


        $vehicle_details = DB::connection('sqlsrv2')
            ->table('masters')
            ->select('MODEL', DB::raw('[PLATE No#] as PLATE_NO'))
            ->whereIn(DB::raw('[PLATE No#]'), $allVehiclePlates)
            ->get();

   

        return response()->json([
            'header' => $header,
            'detail' => $detail,
            'remarks' => $remarks,
            'requestor' => $requestor,
            'dept_approver' => $dept_approver,
            'dispatcher' => $dispatcher,
            'driver_details'=> $driver_details,
            'vehicle_details'=> $vehicle_details,

        ]);
    }

    public function show_dispatch(string $id)
    {
        $request_details = DB::connection('sqlsrv')
    ->table('v_requests as a')
    ->select('a.departure_time',
        'a.destination_from',
        'a.destination_to',
        'a.requested_hrs',
        'a.trip_type',
        'a.status_desc',
        'a.vehicle',
        'a.departure_time',
        'a.division_manager',
        'a.gsd_manager_fullname',
        'a.dept_approver_fullname',
        'b.id',
        'b.dispatch_reference',
        'b.origin_purpose',
        'b.request_reference',
        'b.requestor_dept',
        'b.requestor_name',
        'b.passengers',
        'b.purpose',
        'b.created_at'
        
    )
    ->leftJoin(DB::raw("(SELECT *,
                            value AS split_id
                         FROM dispatch_table
                         CROSS APPLY STRING_SPLIT(header_id, ',')
                        WHERE id = $id) as b")
                         ,
        DB::raw("a.header_id"), "=", DB::raw("TRY_CAST(b.split_id AS INT)")
        )
        ->whereNotNull('b.id')
        ->first();

        $remarks = Remark::where('request_header_id', $id)->get();

            $allDriversIds = DB::connection('sqlsrv')
                ->table('dispatch_table')
                ->where('id', $id)
                ->pluck('drivers_details'); 


            $driver_details = DB::connection('sqlsrv1')
                ->table('driver_details')
                ->select('employee_id', 'last_name', 'first_name')
                ->whereIn('employee_id', $allDriversIds)
                ->get();


            $allVehiclePlates = DB::connection('sqlsrv')
                ->table('dispatch_table')
                ->where('id', $id)
                ->pluck('vehicle_details'); 


            $vehicle_details = DB::connection('sqlsrv2')
                ->table('masters')
                ->select('MODEL', DB::raw('[PLATE No#] as PLATE_NO'))
                ->whereIn(DB::raw('[PLATE No#]'), $allVehiclePlates)
                ->get();

   
            //return $request_details;

        return response()->json([
             'request_details' =>$request_details, 
             'driver_details'=> $driver_details,
             'vehicle_details'=> $vehicle_details,
             'remarks'=> $remarks,

         ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $header = RequestHeader::where('id', $id)->first();

        $details = RequestDetail::where('request_header_id', $id)->where('is_removed', 0)->get();
        $passengers = $details->pluck('passengers')->all();

       
        
        return view('admin.forms.edit', compact('header', 'details', 'passengers'));
    }

    public function assign_vehicle(string $id)
    {
        $header = RequestHeader::where('id', $id)->first();

        $details = RequestDetail::where('request_header_id', $id)->where('is_removed', 0)->get();
        $passengers = $details->pluck('passengers')->all();
        $vehicle_types = DB::connection('sqlsrv')->select("SELECT * FROM vehicle_types_tbl where header_id ='$id'");

        $select_passengers = DB::connection('sqlsrv3')->select("SELECT * FROM viewHREmpMaster");
        
        $vehicles_request = DB::connection('sqlsrv')
            ->table('vehicle_types_tbl')
            ->where('header_id',$id)
            ->get();

        return view('admin.forms.dispatch_form_new', compact('header','vehicles_request', 'details', 'passengers', 'vehicle_types','select_passengers'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //$user_dept = session('user_department'); // To change
        $user_id = session('user_id'); // To change
        $purpose = $request->purpose;
        $header_id = $id;
        $details_id = $request->id;
        $datetime = $request->start_datetime;
        $dateend = $request->end_datetime;
        $requested_hrs = $request->requested_hrs;
        $destination_from = $request->destination_from;
        $destination_to = $request->destination_to;
        $trip_type = $request->trip_type;
        $is_confidential = $request->is_confidential;
        $is_emergency = $request->is_emergency;
        $is_nightdrive = $request->is_nightdrive;
        $passengers = $request->passengers != null ? $request->passengers : [[]];
        $requested_vehicle = $request->vehicle_type; 



        //dd(request()->all());

        // // dd($datetime,
        // //     $requested_hrs,
        // //     $destination_from,
        // //     $destination_to,
        // //     $passengers,
        // // );

        // // Validate single inputs first to increase efficiency and remove redundancy
        // // $validator = Validator::make([
        // //         // Values to validate
        // //         'purpose' => $purpose,
        // //         'requested_vehicle' => $requested_vehicle,
        // //         'passengers' => $passengers,
        // //     ],
        // //     [
        // //         // Validators
        // //         'purpose' => 'required',
        // //         'requested_vehicle' => 'required',
        // //         'passengers' => 'required'
        // //     ]
        // // );

        // $validator = Validator::make([
        //         // Values to validate
        //         'purpose' => $purpose,
        //         'requested_vehicle' => $requested_vehicle,
        //         'datetime' => $datetime,
        //         'requested_hrs' => $requested_hrs,
        //         'destination_from' => $destination_from,
        //         'destination_to' => $destination_to,
        //         'passengers' => $passengers,
        //     ],
        //     [ // Rules
        //         'purpose' => 'required',
        //         'requested_vehicle' => 'required',
                
        //         'datetime' => 'required',
        //         'datetime.*' => 'required|date|after:yesterday',
        //         'requested_hrs' => 'required',
        //         'requested_hrs.*' => 'required',
        //         'destination_from' => 'required',
        //         'destination_from.*' => 'required',
        //         'destination_to' => 'required',
        //         'destination_to.*' => 'required',

        //         'passengers' => 'required',
        //         'passengers.*' => 'required',
        //     ],
        //     [ // Fail Messages
        //         'datetime.*.required' => "A 'Departure' field is empty! Please remove the row or enter your departure time.",
        //         'datetime.*.after:yesterday' => "Invalid 'Departure' date! Please select a date that is today or later.",
        //         'requested_hrs.*.required' => "A 'Requested Hours' field is empty! Please remove the row or enter your requested hours.",
        //         'destination_from.*.required' => "A 'Destination From' field is empty! Please remove the row or enter your origin.",
        //         'destination_to.*.required' => "A 'Destination To' field is empty! Please remove the row or enter your passengers.",
        //         'passengers.*.required' => "A 'Passenger' field is empty! Please remove the row or enter your passengers."
        //     ]
        // );

        $data = [
            'purpose' => $purpose,
            'datetime' => $datetime,
            'dateend' =>$dateend,
            'requested_hrs' => $requested_hrs,
            'destination_from' => $destination_from,
            'destination_to' => $destination_to,
            'trip_type' => $trip_type,
            'passengers' => $passengers,
            'requested_vehicle' => $requested_vehicle
        ];
        
        $validation_result = $this->request_validation('initialValidation', $data); 
        
        if ($validation_result['status'] == 'fail')
        {
            $failed_initial = [];
            foreach($validation_result['failed_input'] as $input) {
                array_push($failed_initial, explode('.', $input)[0]);
            }

            return response()->json([
                'status' => 'error', 
                'message' => $validation_result['message'], 
                'validation' => 'initial',
                'input' => $failed_initial
            ]);

            // $failed = explode('.', $validation_result['failed_input']);

            // return response()->json([
            //     'status' => 'error', 
            //     'message' => $validation_result['message'], 
            //     'validation' => 'initial', 
            //     'row' => $failed[1], 
            //     'input' => $failed[0]
            // ]);
        }

        $no_of_rows = max(
             isset($datetime) ? count($datetime) : 0,
            isset($dateend) ? count($dateend) : 0,
            isset($requested_hrs) ? count($requested_hrs) : 0,
            isset($destination_from) ? count($destination_from) : 0,
            isset($destination_to) ? count($destination_to) : 0,
            isset($trip_type) ? count($trip_type) : 0,
            isset($passengers) ? count($passengers) : 0,
            isset($requested_vehicle) ? count($requested_vehicle) : 0
        );
        $count = 0;

        // // If Validation fails, send to check_fail function to handle error message content
        // if($validator->fails())
        // {
        //     $message = $this->check_fail($validator, 'initial');
        //     return back()->with('error', $message)->with('row_count', $no_of_rows)->withInput();
        // }

        //Remove null rows
        for ($index = 0; $index < $no_of_rows; $index++) {
            if (
                (!isset($datetime[$index]) || $datetime[$index] === null) &&
                (!isset($dateend[$index]) || $dateend[$index] === null) &&
                (!isset($requested_hrs[$index]) || $requested_hrs[$index] === null) &&
                (!isset($destination_from[$index]) || $destination_from[$index] === null) &&
                (!isset($destination_to[$index]) || $destination_to[$index] === null) &&
                (!isset($trip_type[$index]) || $trip_type[$index] === null) &&
                (!isset($requested_vehicle[$index]) || $requested_vehicle[$index] === null) &&
                (!isset($passengers[$index][0]) || $passengers[$index][0] === 'null')
            ) {
                unset($datetime[$index]);
                unset($dateend[$index]);
                unset($requested_hrs[$index]);
                unset($destination_from[$index]);
                unset($destination_to[$index]);
                unset($trip_type[$index]);
                unset($requested_vehicle[$index]);
                
                if (isset($passengers[$index])) {
                    unset($passengers[$index]);
                }

                $count++;
            }
        }
        $no_of_rows -= $count;
        
        // Reindex the arrays
        array_values($datetime);
        array_values($dateend);
        array_values($requested_hrs);
        array_values($destination_from);
        array_values($destination_to);
        array_values($trip_type);
        array_values($requested_vehicle);
        array_values($passengers);

        // // Make map of filtered inputs to validate
        // $inputs = [
        //     'user_dept' => $user_dept,
        //     'user_id' => $user_id,
        //     'departure_time' => $datetime,
        //     'requested_hrs' => $requested_hrs,
        //     'destination_from' => $destination_from,
        //     'destination_to' => $destination_to,
        //     'passengers' => $passengers
        // ];

        // $validator = Validator::make($inputs, [
        //     'user_dept' => 'required',
        //     'user_id' => 'required',
        //     'departure_time' => 'required|min:1',
        //     'departure_time.*' => 'date',
        //     'requested_hrs' => 'required|min:1',
        //     'requested_hrs.*' => 'decimal:0,2',
        //     'destination_from' => 'required|min:1',
        //     'destination_to' => 'required|min:1',
        //     'passengers' => ['required']
        // ]);

        // if($validator->fails())
        // {
        //     $message = $this->check_fail($validator, 'latter');
        //     return back()->with('error', $message)->withInput();
        // }

        $data = [
            'datetime' => $datetime,
            'dateend' => $dateend,
            'requested_hrs' => $requested_hrs,
            'destination_from' => $destination_from,
            'destination_to' => $destination_to,
            'trip_type' => $trip_type,
            'vehicle_type' => $requested_vehicle,
            'passengers' => $passengers,
        ];
        
        $validation_result = $this->request_validation('latterValidation', $data);
        if ($validation_result['status'] == 'fail')
        {
            $failed_rows = [];
            $failed_inputs = [];

            foreach($validation_result['failed_input'] as $input) {
                $fail = explode('.', $input);

                array_push($failed_rows, $fail[1]);
                array_push($failed_inputs, $fail[0]);
            }
            
            return response()->json([
                'status' => 'error', 
                'message' => $validation_result['message'], 
                'validation' => 'latter', 
                'row' => $failed_rows,
                'input' => $failed_inputs
            ]);

            // $failed = explode('.', $validation_result['failed_input']);
            
            // return response()->json([
            //     'status' => 'error', 
            //     'message' => $validation_result['message'], 
            //     'validation' => 'latter', 
            //     'row' => $failed[1], 
            //     'input' => $failed[0]
            // ]);
        }

        // TESTING BLOCK
        // dd($data, $validation_result);

        // Update the header
        RequestHeader::where('id', $header_id)
                    ->update([
                        'purpose' => $purpose,
                        'is_confidential'=> $is_confidential,
                        'is_emergency'=>$is_emergency,
                        'is_nightdrive'=>$is_nightdrive
                    ]);

        // Make a map of the inputs
        $map_count = 0;
        $detail_map = [];
        while($map_count < count($datetime))
        {
            array_push(
                $detail_map,
                array(
                    "id" => $details_id[$map_count] ?? null,
                    "datetime" => $datetime[$map_count],
                    "end_time" => $dateend[$map_count],
                    "requested_hrs" => $requested_hrs[$map_count],                    
                    "destination_from" => $destination_from[$map_count],
                    "destination_to" => $destination_to[$map_count],
                    "passengers" => $passengers[$map_count] ?? null,
                    "trip_type" => $trip_type[$map_count],
                    "vehicle_type" => $requested_vehicle[$map_count]
                )
            );
                
            $map_count++;
        }

        // Fetch existing details ordered by id
        $existing_details = RequestDetail::where('request_header_id', $header_id)->orderBy('id')->get();
        // return response()->json(['data' => $existing_details]);
        
        $existing_details_ids = RequestDetail::where('request_header_id', $header_id)->orderBy('id')->select('id')->get()
        ->map(function($res) {
            return $res->id;
        });
        // ->toArray();

                  
       


        // Batch Insert/Update TEST
        // foreach ($detail_map as $detail) {
        for ($detail_index = 0; $detail_index < count($detail_map); $detail_index++) {
            $detail = $detail_map[$detail_index];
            $passengers_string = is_array($detail["passengers"])
            ? implode("/", $detail["passengers"])
            : $detail["passengers"];

            if(isset($existing_details_ids[$detail_index])) 
            {
                RequestDetail::where('id', $existing_details_ids[$detail_index])->update([
                    'departure_time' => Carbon::parse($datetime[$detail_index]),
                    'end_time'       => Carbon::parse($dateend[$detail_index]),
                    'requested_hrs' => $detail['requested_hrs'],
                    'destination_from' => $detail['destination_from'],
                    'destination_to' => $detail['destination_to'],
                    'passengers' => $passengers_string,
                    'is_removed' => 0,
                    'trip_type' => $detail['trip_type'],
                    'vehicle_type' => $detail['vehicle_type'],
                ]);

                unset($existing_details_ids[$detail_index]);
            } 
            else 
            {
                RequestDetail::create([
                    'request_header_id' => $header_id,
                   'departure_time' => Carbon::parse($datetime[$detail_index]),
                    'end_time'       => Carbon::parse($dateend[$detail_index]),
                    'requested_hrs' => $detail['requested_hrs'],
                    'destination_from' => $detail['destination_from'],
                    'destination_to' => $detail['destination_to'],
                    'passengers' => $passengers_string,
                    'is_removed' => 0,
                    'trip_type' => $detail['trip_type'],
                    'vehicle_type' => $detail['vehicle_type']
                ]);
            }
        }


        if(isset($existing_details_ids) && !empty($existing_details_ids))
        {
            // Marking Excess Rows as Removed
            RequestDetail::where('request_header_id', $header_id)
                        ->whereIn('id', $existing_details_ids)
                        ->update([
                            'is_removed' => 1
                        ]);
        }

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'testes',
        //     'new_input' => $detail_map,
        //     'existing_input' => $existing_details,
        //     'IDS1' => $existing_details_ids, 
        // ]);
        // dd('asdf');        

        // // Batch Insert/Update
        // $updated_details = [];
        // $detail_count = 0;
        // foreach ($detail_map as $detail) {
        //     $is_update = false;
        //     $passengers_string = implode("/", $detail["passengers"]);
            
        //     if(isset($existing_details[$detail_count])) 
        //     {
        //         $is_update = $existing_details[$count]->update([
        //             'departure_time' => Carbon::createFromFormat('Y-m-d\TH:i',$detail['datetime']),
        //             'requested_hrs' => $detail['requested_hrs'],
        //             'destination_from' => $detail['destination_from'],
        //             'destination_to' => $detail['destination_to'],
        //             'passengers' => $passengers_string,
        //             'is_removed' => 0,
        //             'trip_type' => $detail['trip_type'],
        //         ]);
        //     } 
        //     else 
        //     {
        //         $new_detail = RequestDetail::create([
        //             'request_header_id' => $header_id,
        //             'departure_time' => Carbon::createFromFormat('Y-m-d\TH:i',$detail['datetime']),
        //             'requested_hrs' => $detail['requested_hrs'],
        //             'destination_from' => $detail['destination_from'],
        //             'destination_to' => $detail['destination_to'],
        //             'passengers' => $passengers_string,
        //             'is_removed' => 0,
        //             'trip_type' => $detail['trip_type'],
        //         ]);
        //     }

        //     array_push(
        //         $updated_details, 
        //         $is_update ? 
        //             $existing_details[$count]['id'] : $new_detail->id
        //     );
        //     $detail_count++;
        // }
        
        // // Marking Excess Rows as Removed
        // RequestDetail::where('request_header_id', $header_id)
        //             ->whereNotIn('id', $updated_details)
        //             ->update([
        //                 'is_removed' => 1
        //             ]);
       Session::flash('success', 'Successfully updated request!');
        return response()->json([
            'status' => 'success',
            'message' => 'Request successfully updated!',
            'redirect' => url('/') 
        ]);
    }

    public function update_status(Request $request)
    {
        // Update status of selected Request using id
        $id = $request->id;
        $status = (int) $request->status;

        $message = "";

        $request_header = RequestHeader::where('id', $id)->first();

        if (!$request_header) {
            $message = "Request updating failed!";
            session()->flash('error', $message);
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        switch ($status) {
            case 1:
                RequestHeader::where('id', $id)->update(['status' => $status]);
                $message = 'Request saved!';
                break;


           case 1011:
            $url = url('VBS/public/login');
            $parts = parse_url($url);
            $host = $parts['host'];
            $port = $parts['port'] ?? ($parts['scheme'] === 'https' ? 443 : 80);
            $fp = @fsockopen($host, $port, $errno, $errstr, 2);
            if (!$fp) {
                throw new \Exception("API is unreachable: $errstr ($errno)");
            }
            fclose($fp);
        
        
        RequestHeader::where('id', $id)->update(['status' => $status]);
        $this->post_to_api($id);

        $request_header = RequestHeader::find($id);
        $message = $request_header->is_resubmitted == 1 ? 'Request resubmitted!' : 'Request posted!';

        //return response()->json(['status' => 'success', 'message' => $message]);
    break;


    case 3:
        RequestHeader::where('id', $id)->update(['status' => $status]);
        $message = 'Request approved!';
        break;

    case 4:
        RequestHeader::where('id', $id)->update(['status' => $status]);

        $query = DB::connection('sqlsrv')->table('driver_vehicle_details')->where('header_id', $id)
        ->update(['returned' => 'Yes','returned_date' => now()]);
        $message = 'Request completed!';
        break;
        
    case 5:
        RequestHeader::where('id', $id)->update(['status' => $status]);
        $message = 'Request on hold!';
        break;

    case 1010:
        RequestHeader::where('id', $id)->update(['status' => $status]);
        $message = 'Request was marked as CLOSE!';
        break;

    default:
        $message = 'Unknown status.';
        session()->flash('error', $message);
        return response()->json(['status' => 'error', 'message' => $message]);
        }

        session()->flash('success', $message);
        return response()->json(['status' => 'success', 'message' => $message]);
            }

    private function check_fail($validator, $section)
    {
        $errors = $validator->errors();
        
        $error_keys = [];
        foreach($errors->toArray() as $key => $message) {
            array_push($error_keys, $key);
        }
        
        // If Validator failed on Purpose and Requested Vehicle
        if($section == 'initial')
        {
            if($errors->has('purpose'))
            {
                return 'Request must include a purpose!';
            }

            if($errors->has('requested_vehicle'))
            {
                return 'Please select a vehicle to request!';
            }

            if($errors->has('datetime.*'))
            {
                return  "Invalid 'Departure' date! Please select a date that is today or later.";
            }
            if($errors->has('dateend.*'))
            {
                return 'Date end time invalid! Please select a end date.';
            }
            if($errors->has('driver_details.*'))
            {
                return 'Please select a driver.';
            }
            if($errors->has('vehicle_details.*'))
            {
                return 'Please select a vehicle.';
            }

            return $errors->first();
        } 
        elseif($section == 'latter')
        {   
            if($errors->has('datetime'))
            {
                return 'Departure time not added or incomplete! Please input your preferred departure time.';
            }
            
            if($errors->has('dateend.*'))
            {
                return 'Departure time invalid! Please select a departure time that is today or later.';
            }

            if($errors->has('datetime.*'))
            {
                return 'Departure time invalid! Please select a departure time that is today or later.';
            }

            if($errors->has('requested_hrs.*'))
            {
                return 'Requested hours not added or end date was not selected! Please input your preferred duration in hours.';
            }

            if($errors->has('destination_from.*'))
            {
                return 'Starting location not added or incomplete! Please input your starting location.';
            }
            
            if($errors->has('destination_to.*'))
            {
                return 'Destination not added or incomplete! Please input your destination.';
            }
            
            if($errors->has('trip_type.*'))
            {
                return 'Trip type not selected! Please input your trip type.';
            }
          
            if($errors->has('passengers.*'))
            {
                return 'Passengers not added or incomplete! Please input your passengers.';
            }
             if($errors->has('vehicle_details.*'))
            {
                return 'Please select a vehicle.';
            }

            return $errors->first();
        }
        elseif('api-status')
        {
            if($errors->has('id'))
            {
                return 'Request ID not found!';
            }

            if($errors->has('status'))
            {
                return 'Request status not found!';
            }
        }
    }

    public function post_to_api($request_header_id)
    {
        $request_header = DB::connection('sqlsrv')->select("SELECT * FROM [request_headers] WHERE id = ?", [$request_header_id])[0];
        $transid = $this->generateTransId($request_header->id);

        
        $vbs_user = User::where('id', $request_header->user_id)->first();

        $name = ucwords(strtolower($vbs_user->full_name));

        //$url = 'http://127.0.0.1:8000/api/wfs/vbs_post.php';
        
        //$url ='http://mlsvrvhcbooking/api/wfs/vbs_post.php';
        $url = url('api/wfs/vbs_post.php');
        
        $params = [
            'token'          => config('app.key'),
            'type'           => 'VBS',
            'refno'          => $request_header->id,
            'sourceapp'      => 'Vehicle Booking System',
            'sourceurl'      => request()->root() . '/review/' . $request_header_id,
            'requestor'      => $name,
            'department'     => $request_header->requesting_dept ?? 'N/A',
            'transid'        => $transid,
            'email'          => $vbs_user->email ?? null,
            'purpose'        => $request_header->purpose ?? 'N/A',
            'name'           => $name,
            'approval_url'   => null,
            'nightdrive'   => $request_header->is_nightdrive,
            'is_resubmitted' => $request_header->is_resubmitted ?? 0,
        ];

        $response = Http::asForm()->post($url, $params);


        if ($response->successful()) {
             return response()->json([
                 'status' => 'success',
                 'message' => 'VBS POST sent successfully',
                 'transid' => $transid]);
         } 
                
                 //return response()->json(json_decode($response, true));
         }

     public function post_dispatch_api($id)
    {
        $dispatch_details = DB::connection('sqlsrv')
        ->table('dispatch_table')
        ->where('id', $id)
        ->first();

            $headerIds = explode(',', $dispatch_details->header_id);
            $firstHeaderId = trim($headerIds[0]);

            $header_detail = DB::connection('sqlsrv')
                ->table('request_headers')
                ->where('id', $firstHeaderId)
                ->first();

        //$url = 'http://127.0.0.1:8000/api/wfs/vbs_dispatch_post.php';
        
        //$url ='http://mlsvrvhcbooking/api/wfs/vbs_post.php';
        $url = url('api/wfs/vbs_dispatch_post.php');
        $params = [
            'token'          => config('app.key'),
            'type'           => 'VBS',
            'refno'          => $dispatch_details->id,
            'sourceapp'      => 'Vehicle Booking System',
            'sourceurl'      => request()->root() . '/review/' . $id,
            'requestor'      => $dispatch_details->requestor_name,
            'department'     => $dispatch_details->requestor_dept ?? 'N/A',
            'transid'        => $dispatch_details->dispatch_reference,
            'email'          => $vbs_user->email ?? null,
            'purpose'        => $dispatch_details->purpose ?? 'N/A',
            'name'           => $dispatch_details->requestor_name,
            'nightdrive'     => $header_detail->is_nightdrive,
            'approval_url'   => null,
        ];

        Http::asForm()->post($url, $params);

        
         return response()->json([
                 'status' => 'success',
                 'message' => 'VBS POST sent successfully',
                 'route'   => route('dispatch.create')]); 

         }    

    public function update_to_api($headers_id)
        {
             //$url = 'http://127.0.0.1:8000/api/wfs/vbs_update.php';
            //$url ='http://mlsvrvhcbooking/api/wfs/vbs_update.php';
            $url = url('api/wfs/vbs_update.php');
            // Make sure $headers_id is always an array
            $header_ids = is_array($headers_id) ? $headers_id : [$headers_id];

            // Get the request headers directly
            $request_headers = DB::connection('sqlsrv')
                ->table('request_headers')
                ->whereIn('id', $header_ids)
                ->get();

            $responses = []; // Store all API responses

            foreach ($request_headers as $header) {
                $transid = $header->reference_id ?? null;

                if (!$transid) {
                    $responses[] = [
                        'status' => 'error',
                        'message' => 'Missing transid for request_header_id ' . $header->id
                    ];
                    continue;
                }

                $params = [
                    'transid' => $transid,
                ];

                $response = Http::asForm()->post($url, $params);

                if ($response->successful()) {
                    $responses[] = [
                        'status' => 'success',
                        'message' => 'VBS UPDATE sent successfully',
                        'transid' => $transid,
                        'response' => $response->json()
                    ];
                } else {
                    $responses[] = [
                        'status' => 'error',
                        'message' => 'Failed to update VBS',
                        'transid' => $transid,
                        'response' => $response->body()
                    ];
                }
            }

            // /return response()->json($responses);
        }

    public function show_request_details($id_header)
    {
 
        $request_header = RequestHeader::where('id', $id_header)->first();
 
        $details_query = RequestDetail::where('request_header_id', $id_header)->where('is_removed', 0);
        $details_count = $details_query->count();
        $request_details = $details_query->get();
        $excess_rows = $details_count < 5 ? 5 - $details_count : 0; // If the amount of details is less than 5, add excess rows to meet minimum of 5
       
       
        $requestor = $request_header->user_fullname; // User::where('id', $request_header->user_id)->pluck('full_name')->first();
        $dept_approver = $request_header->dept_approver_fullname; // User::where('id', $request_header->dept_approver)->pluck('full_name')->first();
        $gsd_dispatcher = $request_header->gsd_manager_fullname; // User::where('id', $request_header->gsd_dispatcher_id)->pluck('full_name')->first();
       
        //return $request_details;
        return view('admin.reports.request-detail',compact('request_header', 'request_details', 'details_count', 'excess_rows', 'requestor', 'dept_approver', 'gsd_dispatcher'));
    }

    public function update_from_api(Request $request)
    {
        $reference_id = $request->trans_id;
        $new_status = $request->status;
        $user_id = $request->user_id;
        $designation = $request->user_designation;
    
        $validator = Validator::make(
           [
            'id' => $reference_id,
            'new_status' => $new_status,
            'user' => $user_id,
            'designation' => $designation
           ],
           [
            'id' => 'required',
             'new_status' => 'required',
             'user' => 'required',
             'designation' => 'required'
           ]
        );

        if($validator->fails())
        {
            $message = $this->check_fail($validator, 'api-status');
            
            $data = [
                'message' => $message
            ];

            return response()->json($message);
        }

        $request_header = RequestHeader::where('reference_id', $reference_id);

        if ($designation == 'MANAGER'){
            $request_header = $request_header->update([
                                    'status' => $new_status,
                                    'dept_approver_id' => $user_id,    
                                ]);
        } else {
            $request_header = $request_header->update([
                                    'status' => $new_status,
                                    'dept_approver_id' => $user_id,    
                                ]);
        }

        $message = $request_header ? "Request status update successful!" : "Request status update failed!";
        
        $data = [
            'message' => $message
        ];

        return response()->json($data);
    }

    private function generateTransId($id)
    {
        $no_of_digits = strlen($id);

        $zeroes = '';
        
        for ($count = 0; $count < 9 - $no_of_digits; $count++) {
            $zeroes .= '0';    
        }

        $trans_id = 'VBS-' . $zeroes . $id;
        
        return $trans_id;
    }
    private function dispatchTransId($id)
    {
        $no_of_digits = strlen($id);

        $zeroes = '';
        
        for ($count = 0; $count < 9 - $no_of_digits; $count++) {
            $zeroes .= '0';    
        }

        $trans_id = 'D-VBS-' . $zeroes . $id;
        
        return $trans_id;
    }
    
    public function getUserDetails($fullname)
    {
        $params = ['emp' => 'cayetano']; //$fullname]; 
        $url = url('api/hris-api.php');

        $response = Http::get($url, $params);
         
        return response()->json(json_decode($response, true));
    }

    

    private function request_validation($type, $data)
    {
        switch($type)
        {
            case 'initialValidation':
                $validator = Validator::make($data,[
                    'purpose' => 'required',
                    'requested_vehicle' => 'required',
                    'datetime' => 'required',
                    'dateend' =>'required',
                    'requested_hrs' => 'required',
                    // 'destination_from' => 'required',
                    // 'destination_to' => 'required',
                     'passengers' => 'required',
                     
                ]);
                break;

            case 'latterValidation':
                $min_date = Carbon::now()->format('Y-m-d');
                $null_passengers = 'null';
                $validator = Validator::make($data, [
                    'datetime' => 'required|min:1',
                    'datetime.*' => ['required', 'date',"after_or_equal:$min_date"],
                    'requested_hrs' => 'required|min:1',
                    'requested_hrs.*' => 'required|decimal:0,2',
                    'destination_from' => 'required',
                    'destination_from.*' => 'required|min:1',
                    'destination_to' => 'required',
                    'destination_to.*' => 'required|min:1',
                    'trip_type' => 'required',
                    'trip_type.*' => 'required|min:1',
                    'passengers' => 'required',
                ]);
                break;
        }
        
        // if(!$validator->fails() && $type != 'initialValidation'){dd($data, $type, 'success');}
        
        if($validator->fails())
        {
            $section = $type == 'initialValidation' ? 'initial' : 'latter';
            $message = $this->check_fail($validator, $section);

            $fail_indexes = [];
            foreach($validator->errors()->messages() as $input => $val) {
                foreach($val as $index => $err_message) {
                    array_push($fail_indexes, $input . '.' . $index);
                }
            }
            // dd($section, $validator->errors(), $data);
            // $send_failed = $type == 'initialValidation' ? $fail_indexes
            return ['status' => 'fail', 'message' => $message, 'failed_input' => $fail_indexes];
        } else {
            return ['status' => 'success', 'message' => 'Passed validation'];
        }
    }

     

}

