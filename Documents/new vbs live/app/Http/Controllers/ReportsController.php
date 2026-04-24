<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Status;
use App\Models\RequestDetail;
use App\Models\RequestHeader;
use App\Models\RequestView;
use App\Models\VehicleType;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = RequestHeader::query()->join('users', 'users.id', '=', 'request_headers.user_id')->select('request_headers.*', 'users.full_name');
        
        if (isset($_GET['dept'])) {
            if ($_GET['dept'] != '') {
                $dept = urldecode($_GET['dept']);
                $query = $query->where('requesting_dept', 'LIKE', '%' . $dept . '%');
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

            $details = RequestDetail::whereIn('request_header_id', $header_ids)->get();

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

        if(
            isset($_GET['dept']) ||
            isset($_GET['statuses']) ||
            isset($_GET['dateRange'])||
            isset($_GET['vehicle'])
        ) {
            $request_headers = $query->get();
        } else {
            $request_headers = []; // RequestHeader::all();
        }

        $statuses = Status::where('status', '!=', 'DISAPPROVED')->get();
        $departments = RequestHeader::distinct()->pluck('requesting_dept');
        $vehicle_types = VehicleType::all()->pluck('type');

        $saved_count = $query->where('status', 1)->count();
        $posted_count = $query->where('status', 2)->count();
        $approved_count = $query->where('status', 3)->count();
        $completed_count = $query->where('status', 4)->count();
        $hold_count = $query->where('status', 5)->count();
        
        return view('admin.reports.reports', compact('request_headers', 'statuses', 'departments', 'vehicle_types', 'saved_count', 'posted_count', 'approved_count', 'completed_count', 'hold_count'));
    }

    public function print($id) {
        $header = RequestHeader::where('id', 2)->first();
        $details = RequestView::where('header_id', 2)->get();

        // dd($header, $details);
        // return view('admin.reports.request-details-print', compact('header','details'));
        return view('reports.request-details-print', compact('header','details'));

        // $customPaper = array(0, 0, 595.28, 841.89);
        // $pdf = Pdf::loadView('admin.reports.request-details-print', compact('header','details'))->setPaper($customPaper,'landscape');
        // // dd($pdf);
        // return $pdf->download('invoice.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
