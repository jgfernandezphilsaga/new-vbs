@extends('layouts.app')

@section('styles')
    
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
 
    @php
        $selected_vehicles = explode(',', $header->requested_vehicle);
    @endphp

<div class="card">
    <form id="request-form" action="{{ route('update.request', ['id' => $header->id]) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row" style="text-align:center">
                <h5>Edit Vehicle Request</h5>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8 mb-2">
                    <div class="form-group">
                        <div class="form-label">Purpose: </div>
                        <textarea class="form-control form-input" name="purpose" >{{ $header->purpose }}</textarea>
                    </div>
                    <div class="form-label small"  style="font-size: 12px;">Check if the trip was confidential</div>
                            @php
                        $isConfidential = old('check', $header->is_confidential ?? 0); // fallback to 0 if not set
                    @endphp
                    <input type="hidden" id="is_emergency" class="form-control form-input" name="is_emergency"  value="{{ $header->is_emergency }}" style="width:100%; font-size:13px;"/>
                    <input type="hidden" id="is_nightdrive" class="form-control form-input" name="is_nightdrive"  value="{{ $header->is_nightdrive }}" style="width:100%; font-size:13px;"/>
                    <div class="form-check" style="font-size: 15px;">
                        <input class="form-check-input" type="checkbox" name="is_confidential" value="1" id="confidentialCheck"
                            {{ $isConfidential == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="confidentialCheck">
                            Confidential
                        </label>
                        </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-label">Vehicle Type: </div>
                     
                        <table class="table table-sm mb-0" id="table-id" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%; font-weight: 500">Type</th>
                                            <th style="width: 50%; font-weight: 500">QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $types = ['Motorcycle', 'Light Vehicle', 'Medium Vehicle', 'Heavy Equipment'];
                                        @endphp

                                        @foreach ($types as $type)
                                        @php
                                            $existing = collect($vehicle_types)->firstWhere('vehicle_type', $type);
                                        @endphp
                                        <tr>
                                            <td class="py-1">
                                                 <label class="small mb-0">
                                                    <input type="checkbox" name="requested_vehicle[]" value="{{ $type }}"
                                                        {{ $existing ? 'checked' : '' }} />
                                                    {{ $type }}
                                                    <input type="hidden" name="all_vehicle_types[]" value="{{ $type }}">
                                                </label>
                                            </td>
                                            <td class="py-1">
                                                <input type="number" name="vehicle_qty[{{ $type }}]" class="form-control form-control-sm" value="{{ $existing->qty ?? 0 }}" min="0" />
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" style="display:block; overflow-x:auto;">
                        <thead>
                            <th>Time Start</th>
                            <th>Time End</th>
                            <th>Starting Location</th>
                            <th>Destination</th>
                            <th>Trip Type</th>
                            <th>Name of Passenger(s)</th>
                            <th></th>
                        </thead>
                        <tbody id="table-body">
                            @for($i = 0; $i < count($details); $i++)
                                <tr class="data-row" data-row-id="{{ $i }}">
                                    
                                    <input type="hidden" name="id[]" value="{{ $details[$i]->id ?? '' }}"/>
                                    <td>
                                        <input type="datetime-local" id="start_datetime"class="form-control form-input" name="start_datetime[]" value="{{ $details[$i]->departure_time }}" style="width:100%; font-size:13px;" />
                                    </td>
                                     <td>
                                        <input type="datetime-local" id="end_datetime" class="form-control form-input" name="end_datetime[]" value="{{ $details[$i]->end_time }}" style="width:100%; font-size:13px;" required />
                                    </td>
                                        <input type="hidden" class="form-control" name="requested_hrs[]" value="{{ $details[$i]->requested_hrs }}" style="width:100%; font-size:13px;" />
                                    <td>
                                        <input type="text" class="form-control" name="destination_from[]" value="{{ $details[$i]->destination_from }}" style="width:100%; font-size:13px;"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="destination_to[]" value="{{ $details[$i]->destination_to }}" style="width:100%; font-size:13px;" />
                                    </td>
                                    <td>
                                        <select class="form-select form-input trip-select {{ 'readonly-select2'}}" 
                                        name="trip_type[]" style="font-size: 13px;"  >
                                    <option value="">Select</option>
                                    <option value="ONE WAY" {{ $details[$i]->trip_type == 'ONE WAY' ? 'selected' : '' }}>One Way</option>
                                    <option value="ROUND TRIP" {{ $details[$i]->trip_type == 'ROUND TRIP' ? 'selected' : '' }}>Round Trip</option>
                                </select>
                                    </td>
                                    <td>
                                        <select class="form-control emp-select2 {{ 'readonly-select2'}}" id="passengers-{{ $i }}" name="passengers[{{ $i }}][]" multiple true  style="width: 100%" >
                                       
                                        </select>
                                    </td>
                                    <td>
                                            <button class="btn btn-danger" type="button" onclick="removeRow({{ $i }})"><i class="fa-solid fa-circle-minus" style="color:white"></i></button>

                                    </td>
                                </tr>
                            @endfor
                          
                                <tr id="add-btn-row">
                                    <td colspan="7" style="text-align: center">
                                        <button class="btn btn-primary" id="add-btn" type="button">
                                            <i class="fa-solid fa-circle-plus"></i> Add more
                                        </button>
                                    </td>
                                </tr>

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

            <p style="font-size: 0.7rem;">Note: Submit the requisition (2) two days before the scheduled trip and must notify (1) one day before to requesting department if approve or reject.</p>

                <div style="display:flex; flex-direction:row; justify-content:end;">
                    <button class="btn btn-primary me-2 d-flex flex-row align-items-center" id="update-btn" type="submit">
                        <i class="fa-solid fa-paper-plane"></i> 
                        &nbsp;
                        <span id="submit-btn-text">Update</span>
                    </button>

                <button class="btn btn-danger" type="button" onclick="returnToDash()">
                    <i class="fa-solid fa-circle-xmark"></i> Cancel
                </button>
            </div>
                            
        </div>
    </form>
</div>


        </div>
    </div>

<script>
   $('.emp-select2').select2({
    placeholder: "Search employee",
    allowClear: true,
    ajax: {
        url: '/api/get_employees',
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
   </script> 

   <script>

    let count = 1;

    let passengersData = JSON.parse('{!! json_encode($passengers) !!}');

            let initialPassengers = [];
            passengersData.forEach(function (currentValue, selectIndex){ 
                let splitPassengers = currentValue.split('/');

                let row = [];
                splitPassengers.forEach(function (currentPassenger, passengerIndex){
                    let rowPassengersData = new Map();
                    let singlePassengerData = currentPassenger.split('|');

                    let newOption = new Option(singlePassengerData[1], currentPassenger, false, true);
                    $(`#passengers-${selectIndex}`).append(newOption).trigger('change');

                    rowPassengersData.set("id", singlePassengerData[0]);
                    rowPassengersData.set("text", singlePassengerData[1]);

                    row.push(rowPassengersData);
                });
                
                initialPassengers.push(row);
            });

            $(document).ready(function () {
            let selectedVehicles = {!! json_encode($selected_vehicles) !!};

            selectedVehicles.forEach(function (vehicle) {
                let option = new Option(vehicle, vehicle, true, true);
                $('#requested_vehicle').append(option);
            });

            $('#requested_vehicle').trigger('change'); // required for Select2
        });


        let rowCount = $('#table-body').find('tr').length - 1;
        document.addEventListener('DOMContentLoaded', (event) => {
            const reqForm = document.getElementById('request-form');
            const addBtn = document.getElementById('add-btn');
            const updateBtn = document.getElementById('update-btn');
            const tableBody = $('#table-body');
            // const removeBtn = dosucment.getElementById('remove-btn');

            let count = rowCount + 1;

            if (rowCount == 5) {
                $('#add-btn-row').hide();
            }

            addBtn.addEventListener('click', function(event) {
                // const tableBody = document.getElementById('table-body');

            var lastRow = document.querySelectorAll('.data-row');
         var lastRowDate = '', lastHours = '', lastFrom = '', lastTo = '', lastTripType = '';
    
    if (lastRow.length > 0) {
        let last = lastRow[lastRow.length - 1];
        lastRowDate = last.querySelector('input[name="start_datetime[]"]')?.value || '';
        lastRowendDate = last.querySelector('input[name="end_datetime[]"]')?.value || '';
        lastHours = last.querySelector('input[name="requested_hrs[]"]')?.value || '';
        lastFrom = last.querySelector('input[name="destination_from[]"]')?.value || '';
        lastTo = last.querySelector('input[name="destination_to[]"]')?.value || '';
        lastTripType = last.querySelector('select[name="trip_type[]"]')?.value || '';
    }

                var newRow = document.createElement('tr');
                var rowID = getNewRowID(rowCount);
                newRow.classList.add('data-row');
                newRow.setAttribute('data-row-id', rowID);

                newRow.innerHTML = `
                <input type="hidden" name="id[]" value=""/>
                <td>
                    <input type="hidden" name="row_id" value="${rowCount}"/>
                    <input type="datetime-local" class="form-control form-input" name="start_datetime[]" value="${lastRowDate}" readonly style="width:100%; font-size:13px;" required/>
                </td>
                <td>
                    <input type="datetime-local" class="form-control form-input" name="end_datetime[]" value="${lastRowendDate}" style="width:100%; font-size:13px;" readonly />
                    <input type="hidden" class="form-control form-input" name="requested_hrs[]" value="${lastHours}" readonly style="width:100%; font-size:13px;" min="1" required/>
                </td>
                <td>
                    <input type="text" class="form-control form-input" name="destination_from[]" value="${lastFrom}" readonly style="width:100%; font-size:13px;" required/>
                </td>
                <td>
                    <input type="text" class="form-control form-input" name="destination_to[]" value="${lastTo}" readonly style="width:100%; font-size:13px;" required/>
                </td>
                <td>
                    <select class="form-select" name="trip_type[]" style="font-size: 13px;">
                        <option value="${lastTripType}">${lastTripType}</option>
                    </select>    
                </td>
                <td>
                    <select class="form-control emp-select2" id="passengers-${rowCount}" name="passengers[${rowCount}][]" multiple="multiple" style="width: 100%">
                    </select>
                </td>
                <td>
                    <button class="btn btn-danger" type="button" onclick="removeRow(${rowCount})">
                        <i class="fa-solid fa-circle-minus" style="color:white"></i>
                    </button>
                </td>
            `;


            $(newRow).insertBefore($('#add-btn-row'));
                rowCount++;

           const datetimeInput = newRow.querySelector('input[type="datetime-local"]');
            if (datetimeInput) {
                applyDatetimeRestrictions(datetimeInput);
            }

            $(`#passengers-${rowID}`).select2({
            placeholder: "Search Passengers",
            allowClear: true,
            ajax: {
                url: '/api/get_employees',
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
        
            count++;
            if (count == 5) {
                $('#add-btn-row').hide();
            }
        });
        $('.readonly-select2').on('select2:opening select2:selecting select2:unselecting select2:clearing', function (e) {
        e.preventDefault();
    });

    
    $('.readonly-select2').each(function () {
        $(this).next('.select2-container').find('.select2-selection').css('pointer-events', 'none');
    });


    $('.readonly-select2').on('keydown', function (e) {
        e.preventDefault();
    });
    });

    function getNewRowID(count) {
        const values = $('[data-row-id]').map(function () {
            return Number($(this).data('row-id'));
        }).get();

        values.sort((a, b) => a - b);

        for (let num = values[0]; num < values.length; num++) {
            if (num !== values[num]) {
                return num;
            }
        }

        return count;
    }

     function removeRow(rowId) {
            var row = document.querySelector('[data-row-id="'+ rowId +'"]');
            row.remove();
            rowCount--;

            

            if (rowId < 5) {
                $('#add-btn-row').show();
            }
        }

    function returnToDash() {
    window.location.href = "{{ url('/') }}";
}
    </script>
      
   <script>
function applyDatetimeRestrictions() {
    const two = n => n.toString().padStart(2, '0');

    const asLocalISO = (d) =>
        `${d.getFullYear()}-${two(d.getMonth() + 1)}-${two(d.getDate())}` +
        `T${two(d.getHours())}:${two(d.getMinutes())}`;

    const now = new Date();
    now.setDate(now.getDate() + 1); // Tomorrow
    now.setHours(9, 0, 0, 0); // 09:00 AM

    const inputs = document.querySelectorAll('input[type="datetime-local"][name="datetime[]"]');
    inputs.forEach(input => {
        input.min = asLocalISO(now);

        input.addEventListener('change', function () {
            if (!this.value) return;
            const dt = new Date(this.value);
            dt.setMinutes(0, 0, 0);
            this.value = asLocalISO(dt);
        });
    });
}
    </script>
    
 <script>
document.addEventListener('DOMContentLoaded', function () {
    const updateBtn = document.getElementById('update-btn');
    const tableBody = document.querySelector('#table-body');

    if (!updateBtn || !tableBody) {
        console.error("updateBtn or tableBody is missing.");
        return;
    }

    updateBtn.addEventListener('click', function(event){
        event.preventDefault(); 
        $('#submit-btn').prop('disabled', true);
        $('#submit-btn-text').text('Updating...');

        $(tableBody).children()
            .not('#add-btn-row')
            .each(function(index, element) {
                var isRowChanged = false;

                $(this).children()
                    .not('#remove-btn-col')
                    .each(function(innerIndex, rowElement) {
                        let inputElement = $(this).find('input').first().val();
                        if(inputElement != '' && inputElement != undefined) {
                            isRowChanged = true;
                        }
                        
                        let selectElement = $(rowElement).children(':first-child');
                        let selectValues = selectElement.val();
                        if (selectElement.not('.trip-select').is('select') && (selectValues.length == 0 || selectValues[selectValues.length - 1] == undefined)) {
                            var nullOption = new Option('NULL', 'null', false, false);
                            selectElement.append(nullOption).trigger('change');
                            selectElement.val('null').trigger('change');
                        }
                    });

                if(isRowChanged) {
                    $(this).children()
                        .not('#remove-btn-col')
                        .each(function(innerIndex, element) {
                            $(this).find('input, select').first().prop('required', false);
                        });
                }
            });

        let requestData = new FormData($('#request-form').get(0));

        $(tableBody).children().not('#add-btn-row').each(function(index, row) {
            let rowSelect2 = $(row).children().find('.emp-select2');
            let val = rowSelect2.val();
            if (val && (val[val.length - 1] === 'null' || val[val.length - 1] === undefined)) {
                rowSelect2.val(null).trigger('change');
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            }
        });

        $.ajax({
            url: '/request/update/{{ $header->id }}',
            method : 'POST',
            data: requestData,
            processData: false,
            contentType: false,
            success: function(response){
                const protocol = window.location.protocol;
                const hostname = window.location.hostname;
                const port = (window.location.port && window.location.port !== undefined) ? `${window.location.port}` : '';
                if (response.status !== 'error') {
                    triggerToast(response.status, response.message);
                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1000);
                    }
                } else {
                    console.log('error');
                    console.log('error at: ' + response.validation + '|' + response.input + '|' + response.row);

                    if(response.validation == 'initial') { 
                        let reqFormElement = $('#request-form');
                        let listenerType = response.input == 'purpose' ? 'keydown' : 'change';
                        
                        reqFormElement.find(`[name="${response.input}"]`).css({
                            'border-color': '#f1aeb5',
                            'background-color': '#f8d7da'
                        });

                        reqFormElement.on(listenerType, `[name="${response.input}"]`, function() {
                            $(this).css({
                                'border-color': '#dee2e6',
                                'background-color': '#ffffff'
                            })
                        });
                    } else if (response.validation == 'latter') {
                        let failedRows = response.row;
                        let failedInputs = response.input;

                        failedRows.forEach(function(element, failedIndex) {
                            $(tableBody).children()
                                .not('#add-btn-row')
                                .each(function(index, row) {
                                    if(index == failedRows[failedIndex]) {
                                        if(failedInputs[failedIndex] == 'passengers') {
                                            let selectInput = $(row).find(`[name="${failedInputs[failedIndex]}[${failedRows[failedIndex]}][]"]`);
                                            let spanSelect = selectInput.siblings().find('.select2-selection--multiple');

                                            spanSelect.addClass('form-input');
                                            spanSelect.css({
                                                'border-color': '#f1aeb5',
                                                'background-color': '#f8d7da'
                                            });
                                            
                                            selectInput.on('select2:select', function() {
                                                spanSelect.css({
                                                    'border-color': '#dee2e6',
                                                    'background-color': '#ffffff'
                                                });
                                            });
                                        } else {
                                            let listenerType = failedInputs[failedIndex] == 'trip_type' ? 'change' : 'keydown';

                                            $(row).find(`[name="${failedInputs[failedIndex]}[]"]`).css({
                                                'border-color': '#f1aeb5',
                                                'background-color': '#f8d7da'
                                            });
                                            
                                            $(row).on(listenerType, `[name="${failedInputs[failedIndex]}[]"]`, function() {
                                                $(this).css({
                                                    'border-color': '#dee2e6',
                                                    'background-color': '#ffffff'
                                                });
                                            });
                                        }
                                    }
                                });
                        });

                        let errorMessage = response.input.length == 1 ? response.message : 'Multiple errors found. Errors are highlighted in the form.'; 
                        triggerToast(response.status, errorMessage);
                    }      
                }
            },
            error: function(data) {
                triggerToast('error', data.responseText);
            }
        });

        $('#submit-btn').prop('disabled', false);
        $('#submit-btn-text').text('Update');
    });
});
</script>
<script>
$(document).ready(function () {
    $('.select').select2({
        width: '100%'
    });

    $('.readonly-select2').each(function () {
        const $select = $(this);
        const $container = $select.next('.select2-container');
        $select.on('select2:opening select2:selecting select2:unselecting select2:clearing', function (e) {
            e.preventDefault();
        });

        $container.find('.select2-selection').css({
            'pointer-events': 'none',
            'background-color': '#e9ecef',
            'cursor': 'not-allowed'
        });

        $select.on('keydown', function (e) {
            e.preventDefault();
        });
    });
});

 </script>   


<script>
document.addEventListener('DOMContentLoaded', function () {

    function computeRequestedHours(row) {
    const startInput = row.querySelector('input[name="start_datetime[]"]');
    const endInput = row.querySelector('input[name="end_datetime[]"]');
    const hrsInput = row.querySelector('input[name="requested_hrs[]"]');

 
    const emergencyInput = document.querySelector('input[name="is_emergency"]');
    const nightDriveInput = document.querySelector('input[name="is_nightdrive"]');

    if (!startInput || !endInput || !hrsInput) return;

    const start = new Date(startInput.value);
    const end = new Date(endInput.value);

    if (!isNaN(start) && !isNaN(end)) {
        const diffMs = end - start;

        if (diffMs >= 0) {
            const diffHrs = diffMs / (1000 * 60 * 60);
            hrsInput.value = diffHrs.toFixed(2);

            const startHour = start.getHours();
            const endHour = end.getHours();

     
            if (emergencyInput) emergencyInput.value = '';
            if (nightDriveInput) nightDriveInput.value = '';

            if (diffHrs <= 12) {
                if (emergencyInput) emergencyInput.value = 1;
                alert('Marked as emergency trip your request is less than 12hrs. Kindly call Manager for Immediate Approval');
            }
            const isNightDrive =
                diffHrs > 12 ||
                startHour === 5 ||
                startHour >= 17 ||
                endHour >= 17 ||
                endHour <= 5;

            if (isNightDrive && nightDriveInput) {
                nightDriveInput.value = 1;
            }

        } else {
            hrsInput.value = '';
            if (emergencyInput) emergencyInput.value = '';
            if (nightDriveInput) nightDriveInput.value = '';
        }
    } else {
        hrsInput.value = '';
        if (emergencyInput) emergencyInput.value = '';
        if (nightDriveInput) nightDriveInput.value = '';
    }
}

    document.querySelectorAll('.data-row').forEach(row => {
    const startInput = row.querySelector('input[name="start_datetime[]"]');
    const endInput = row.querySelector('input[name="end_datetime[]"]');

    if (startInput && endInput) {
        startInput.addEventListener('change', () => computeRequestedHours(row));
        endInput.addEventListener('change', () => computeRequestedHours(row));
    }
    });

    window.bindHourComputationToRow = function (row) {
        const startInput = row.querySelector('input[name="start_datetime[]"]');
        const endInput = row.querySelector('input[name="end_datetime[]"]');

        if (startInput && endInput) {
            startInput.addEventListener('change', () => computeRequestedHours(row));
            endInput.addEventListener('change', () => computeRequestedHours(row));
        }
    };
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
                url: '/api/get_vehicles/' + id,
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

        // Initialize or reinitialize driver select
        if ($driverSelect.hasClass("select2-hidden-accessible")) {
            $driverSelect.select2('destroy');
        }

        $driverSelect.select2({
            placeholder: "Search Driver",
            allowClear: true,
            maximumSelectionLength: qty,
            ajax: {
                url: '/api/get_drivers',
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
@endsection
