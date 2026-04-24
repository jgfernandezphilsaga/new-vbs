@extends('layouts.base')

@section('content')
    <div class="card p-2">
        <div class="p-dev" id="printable-div">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex flex-row">
                        <div class="flex-grow-1 text-center">
                            <div class="d-flex flex-row align-items-center justify-content-evenly">
                                <img class="modal-left-title-image" src="{{ asset('assets/images/logo/pmc-logo.png') }}" alt="PMC logo">
                                <img class="modal-left-title-image" src="{{ asset('assets/images/logo/mmprc-logo.png') }}" alt="MMPRC logo">
                            </div>
                        </div>
                        <div class="flex-grow-1 flex-shrink-0">
                            <div class="d-flex flex-column align-items-center">
                                        <h6 class="view-details-headers">Philsaga Mining Corporation</h6>
                                       <h6 class="view-details-headers">Mindanao Mineral Processing and Refining Corporation</h6>
                                        <div class="mt-2">
                                            <p class="mt-1 mb-0 view-details-headers" >GENERAL SERVICES DEPARTMENT</p>
                                            <p class="m-0 view-details-headers" >GSD FORM V1</p>
                                            
                                        </div>
                                        <h6 class="my-3 view-details-headers" style="font-weight:bold;">VEHICLE REQUISITION SLIP</h6>
                                    </div> 
                        </div>
                        <div class="flex-grow-1 text-center">
                            <div class="d-flex flex-row align-items-center justify-content-evenly">
                                <img class="modal-right-title-image" src="{{ asset('assets/images/logo/pmc-values.png') }}" alt="PMC values">
                                <img class="modal-right-title-image" src="{{ asset('assets/images/logo/iso-cert.png') }}" alt="PMC ISO cert">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <div class="d-flex flex-row">
                        <p class=" m-0" >Requesting Dept.:</p>
                        <div class="placeholder-glow mx-2 flex-grow-1">
                            <span class="w-100" id="requesting-dept">{{ $request_header->requesting_dept }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex flex-column" style="text-align:end">
                    <div class="d-flex flex-row" style="text-align:end">
                        <p class="m-0 flex-grow-1">Date:</p>
                        <div class="placeholder-glow mx-2">
                            <span class="" id="header-date" style="width: 15em">{{ date('Y-m-d', strtotime($request_header->created_at)) }}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <p class="m-0 flex-grow-1" >Requested Vehicle:</p>
                        <div class="placeholder-glow mx-2">
                            @foreach($request_details as $details)
                            <span class="" id="requested-vehicle" style="width: 15em">{{ $details->vehicle_type }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered mb-1">
                        <thead>
                            <th style="vertical-align:middle; text-align:center; width: 16%">Date</th>
                            <th style="vertical-align:middle; text-align:center; width: 10%">Time of Departure</th>
                            <th style="vertical-align:middle; text-align:center; width: 10%">Requested Hour(s)</th>
                            <th style="text-align:center; width: 44%; padding: 0px" colspan="2">
                                <div class="d-flex flex-column">
                                    <p style="vertical-align:top; border-bottom:solid; border-bottom-width: 1px; border-color: rgb(222, 226, 230)">Destination</p>
                                    <div class="d-flex flex-row">
                                        <p class="col-6">From</p>
                                        <p class="col-6" style="border-left:solid; border-left-width: 0px; border-color: rgb(222, 226, 230)">To</p>
                                    </div>
                                </div>
                            </th>
                            <th style="text-align:center; width: 20%">Name of Passenger(s)</th>
                        </thead>
                        <tbody id="modal-tbody">

                            @foreach($request_details as $detail)
                                <tr id="">        
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="w-100" id=""> {{ date('Y-m-d', strtotime($detail->departure_time)) }}</span>
                                        </div>
                                    </td>
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="w-100" id=""> {{ date('h:i A', strtotime($detail->departure_time)) }}</span>
                                        </div>
                                    </td> 
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="w-100" id=""> {{ $detail->requested_hrs }}</span>
                                        </div>
                                    </td>  
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                            <span class="w-100" id=""> {{ $detail->destination_from }}</span>
                                        </div>
                                    </td>    
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                            <span class="w-100" id=""> {{ $detail->destination_to }}</span>
                                        </div>
                                    </td> 
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="w-100" id=""> 
                                                @php
                                                    $holder = explode('/', $detail->passengers);

                                                    $passengers = '';
                                                    foreach($holder as $value) {
                                                        $temp = explode('|', $value);

                                                        $passengers .= $temp[1];

                                                        if(end($holder) != $value) {
                                                            $passengers .= '&nbsp;';
                                                        }
                                                    }
                                                @endphp
                                                
                                                {!! $passengers !!}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($excess_rows > 0)
                                @for($count = 0; $count < $excess_rows; $count++)
                                    <tr>
                                        <td class="p-1">
                                            <div class="mx-2">&nbsp;</div>
                                        </td>
                                        <td class="p-1">
                                            <div class="mx-2">&nbsp;</div>
                                        </td>
                                        <td class="p-1">
                                            <div class="mx-2">&nbsp;</div>
                                        </td>
                                        <td class="p-1">
                                            <div class="mx-2">&nbsp;</div>
                                        </td>
                                        <td class="p-1">
                                            <div class="mx-2">&nbsp;</div>
                                        </td>
                                        <td class="p-1">
                                            <div class="mx-2">&nbsp;</div>
                                        </td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                    <p style="font-size: 0.7rem;">Note: Submit the requisition (2) two days before the scheduled day trup and must notify (1) one day before to requesting department if approve or reject.</p>
                    <div class="d-flex flex-row my-3">
                        <p class="mb-0">Purpose: </p>
                        <div class="placeholder-glow mx-2 flex-grow-1" id="purpose-content">
                            <span class="w-100" id="purpose">{{ $request_header->purpose }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 d-flex flex-row">
                        <div class="col-md-4 d-flex flex-column px-3">
                            <p class="mb-0">Requested By:</p>
                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                <span class="w-100" id="requested-by">{{ $requestor ?? '' }}</span>
                            </div>
                            <hr class="m-0">
                            <p style="text-align: center">Name/Signature</p>
                        </div>
                        <div class="col-md-4 d-flex flex-column px-3">
                            <p class="mb-0">Approved By: </p>
                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                <span class="w-100" id="approved-by">{{ $dept_approver ?? '' }}</span>
                            </div>
                            <hr class="m-0">
                            <p style="text-align: center">Requesting Dept. Manager</p>
                        </div>
                        <div class="col-md-4 d-flex flex-column px-3">
                            <p class="mb-0">Acknowledged By: </p>
                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                <span class="w-100" id="acknowledged-by">{{ $gsd_dispatcher ?? '' }}</span>
                            </div>
                            <hr class="m-0">
                            <p style="text-align: center">Dispatcher/Dept. Manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection