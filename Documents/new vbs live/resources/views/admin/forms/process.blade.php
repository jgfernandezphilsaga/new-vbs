@extends('layouts.app')

@section('styles')
<style>
#loading-overlay {
    display: none; /* hidden by default */
    position: fixed;
    z-index: 9999;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7);
    
    /* center spinner using flexbox */
    display: flex;
    justify-content: center;
    align-items: center;
}
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                
                <form id="request-form" action="{{ route('save.request')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row" style="text-align:center">
                            <h5>Add Vehicle / Driver</h1>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-8 mb-2">
                                <div class="form-group">
                                    <div class="form-label">Purpose: </div>
                                    {{ $header->purpose }}
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="display:block; overflow-x:auto;">
                                    <thead>
                                        <th style="width: 5%;">Time of Departure</th>
                                        <th style="width: 10%;">Requested Hrs</th>
                                        <th style="width: 10%;">Starting Location</th>
                                        <th style="width: 10%;">Destination</th>
                                        <th style="width: 10%;">Trip Type</th>
                                        <th style="width: 15%;">Name of Passenger(s)</th>
                                        <th style="width: 1%;"></th>
                                    </thead>
                                     <input type="hidden" name="header_id" value="  {{ $header->id}}"/>
                                    <tbody style="width: 100%;" id="table-body">
                                        @if(Session::get('row_count'))
                                            <?php 
                                                // dd($details[0]);
                                            ?>
                                        @endif
                                       @foreach($details as $detail)
                                    <tr class="data-row" data-row-id="{{ $detail->id }}">
                                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}"/>
                                        <td>{{ $detail->departure_time }}</td>
                                        <td>{{ $detail->requested_hrs }}</td>
                                        <td>{{ $detail->destination_from }}</td>
                                        <td>{{ $detail->destination_to }}</td>
                                        <td>{{ $detail->trip_type }}</td>
                                        <td>{{ $detail->passengers }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach

                                        
                                    </tbody>
                                </table>
                            </div>    
                            <div class="col-md-12">
                                <table class="table table-sm mb-0" id="table-id" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th style="width: 25%; font-weight: 500">Type</th>
                                        <th style="width: 15%; font-weight: 500">QTY</th>
                                        <th style="width: 30%; font-weight: 500">Driver Name</th>
                                        <th style="width: 30%; font-weight: 500">Vehicle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehicles_request as $index => $vehicle)
                                        <tr>

                                            <td class="py-1">
                                                <input type="hidden" name="id_vehicle_request[]" class="form-control form-control-sm "
                                                    value="{{ $vehicle->id ?? '' }}" required readonly />
                                                <input type="text" name="requested_vehicle[]" class="form-control form-control-sm "
                                                    value="{{ $vehicle->vehicle_type ?? '' }}" required readonly />
                                            </td>

                                            <td class="py-1">
                                                <input type="number" name="qty[{{ $index }}]" class="form-control form-control-sm vehicle-qty"
                                                    value="{{ $vehicle->qty ?? 0 }}" min="0" readonly />
                                            </td>
                                            <td class="py-1">
                                                <select name="driver_details[{{ $index }}][]" class="form-control emp-select2" multiple style="width: 100%;">
                                                </select>
                                            </td>

                                            <td class="py-1">
                                                <select name="vehicle_unit[{{ $index }}][]" class="form-control emp-select1" multiple style="width: 100%;">
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            </div>
                        </div>
                        <p style="font-size: 0.7rem;">Note: Submit the requisition (2) two days before the scheduled day trup and must notify (1) one day before to requesting department if approve or reject.</p>
                        <div style="display:flex; flex-direction:row; justify-content:end;">
                        <button class="btn btn-primary me-2 d-flex flex-row align-items-center" id="update-btn" type="button">
                            <i class="fa-solid fa-paper-plane"></i> 
                            &nbsp;
                            <p id="submit-btn-text">Submit</p>
                        </button>
                            <button class="btn btn-danger" type="button" onclick="returnToDash()">
                                <i class="fa-solid fa-circle-xmark"></i> Cancel
                            </button>
                        </div>
                    </div>
                      
                </form>
            </div>
    </div>
    <div id="loading-overlay" >
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
</div>
    

    <script>

$('#loading-overlay').fadeIn();

$('#loading-overlay').fadeOut();
        
        // let count = 2; // For rowId
        let rowCount = $('#table-body').find('tr').length - 1;

document.addEventListener('DOMContentLoaded', (event) => {
    const reqForm = document.getElementById('request-form');
    const addBtn = document.getElementById('add-btn');
    const updateBtn = document.getElementById('update-btn');
    const tableBody = $('#table-body');

    // const removeBtn = document.getElementById('remove-btn');

    updateBtn.addEventListener('click', function(event){
        event.preventDefault();

        $('#submit-btn').prop('disabled', true);
        $('#submit-btn-text').text('Updating...');
        $('#loading-overlay').fadeIn(); 

        let requestData = new FormData($('#request-form').get(0));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            }
        });

        $.ajax({
            url: "{{ url('request/save_process') }}",
            method : 'POST',
            data: requestData,
            processData: false,
            contentType: false,
            success: function(response) {
                // alert('success: ' + response);
                // console.log(response);

                window.location.href = '/';
            },
            error: function(xhr) {
                let errorMessage = xhr.status === 422 ? JSON.parse(xhr.responseText).message : `Error: ${xhr.status} ${xhr.statusText}`;
                // alert('error');

                window.location.href = '/';
            },
            complete: function() {
                $('#submit-btn').prop('disabled', false);
                $('#submit-btn-text').text('Save');
                $('#loading-overlay').fadeOut();
            }
        });
    });
});

</script>

<script>
   $(document).ready(function () {
    $('#table-id tbody tr').each(function () {
        const $row = $(this);
        const qty = parseInt($row.find('.vehicle-qty').val()) || 1;

        const $vehicleSelect = $row.find('.emp-select1');
        const $driverSelect = $row.find('.emp-select2');

        let id = "{{ $header->id }}";

        if ($vehicleSelect.hasClass("select2-hidden-accessible")) {
            $vehicleSelect.select2('destroy');
        }

            $vehicleSelect.select2({
            placeholder: "Search Vehicle",
            allowClear: true,
            maximumSelectionLength: qty,
            ajax: {
                url: "{{ url('api/get_vehicles') }}/" + id,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                },
                cache: true
            }
});


        if ($driverSelect.hasClass("select2-hidden-accessible")) {
            $driverSelect.select2('destroy');
        }

        $driverSelect.select2({
            placeholder: "Search Driver",
            allowClear: true,
            maximumSelectionLength: qty,
            ajax: {
                url: "{{ url('api/get_drivers') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                },
                cache: true
            }
        });
    });
});


   </script>

        

    <!-- // function setSelect2Inputs () {
    // const protocol = window.location.protocol;
    // const hostname = window.location.hostname;
    // const port = window.location.port ? `:${window.location.port}` : '';

//     $('.emp-select2').select2({
//         allowClear: true,
//         minimumInputLength: 2,
//         ajax: {
//             url: `/api/hris-api.php`,
//             delay: 2000,
//             dataType: 'json',
//             data: (params) => ({
//                 emp: params.term
//             }),
//             processResults: (data) => {
//                 const results = data.map(emp => ({
//                     id: `${emp.EmpID}|${emp.FullName}`,
//                     text: emp.FullName
//                 }));
//                 return { results };
//             }
//         }
//     });

// }

// $('#vehicle_unit').select2({
//     ajax: {
//         url: '/api/vehicle_api.php',
//         dataType: 'json',
//         delay: 250,
//         data: (params) => ({
//             vehicle: params.term
//         }),
//         processResults: (data) => {
//             //console.log('API response:', data);

//             if (!Array.isArray(data)) {
//                 console.warn('Expected an array but got:', data);
//                 return { results: [] };
//             }

//             const results = data.map(vehicle => ({
//                 id: `${vehicle.PLATE_NO}`,
//                 text: `${vehicle.MODEL} - ${vehicle.PLATE_NO}`
//             }));

//             return { results };
//         },
//         error: function (xhr, status, error) {
//             console.error('Select2 AJAX error:', {
//                 status: status,
//                 error: error,
//                 responseText: xhr.responseText
//             });
//             alert('Error loading vehicle data. Check console for details.');
//         },
//         cache: true
//     },
//     placeholder: 'Search for Model / Plate No.',
//     minimumInputLength: 4
// });

// $('#driver_details').select2({
//     ajax: {
//         url: '/api/vehicle_api.php',
//         dataType: 'json',
//         delay: 250,
//         data: (params) => ({
//             driver_details: params.term
//         }),
//         processResults: (data) => {
//             console.log('API response:', data);

//             if (!Array.isArray(data)) {
//                 console.warn('Expected an array but got:', data);
//                 return { results: [] };
//             }

//             const results = data.map(driver_details => ({
//                 id: `${driver_details.employee_id}`,
//                 text: `${driver_details.last_name} - ${driver_details.first_name}- ${driver_details.employee_id}`
//             }));

//             return { results };
//         },
//         error: function (xhr, status, error) {
//             console.error('Select2 AJAX error:', {
//                 status: status,
//                 error: error,
//                 responseText: xhr.responseText
//             });
//             alert('Error loading driver data. Check console for details.');
//         },
//         cache: true
//     },
//     placeholder: 'Search for driver Last Name / Employee ID.',
//     minimumInputLength: 4
// }); -->
<script>
        function returnToDash() {
            const protocol = window.location.protocol;
            const hostname = window.location.hostname;
            const port = window.location.port ? `:${window.location.port}` : '';
            window.location.href = `${protocol}//${hostname}${port}`;
        }

</script>

@endsection
