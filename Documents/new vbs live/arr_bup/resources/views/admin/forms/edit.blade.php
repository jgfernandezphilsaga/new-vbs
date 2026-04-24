@extends('layouts.app')

@section('styles')
    <style>
.small-datetime {
  width: 175px;   
  padding: 4px;   
  font-size: 14px;
}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form id="request-form" action="{{ route('update.request', ['id' => $header->id]) }}" method="POST">
            @csrf
            <div class="card-body" style="width: 100%;">
                <div class="row" style="text-align:center">
                    <h5>Edit Vehicle Request</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-label">Purpose: </div>
                            <textarea class="form-control form-input" name="purpose">{{ $header->purpose }}</textarea>
                        </div>
                        <div class="form-label small" style="font-size: 12px;">Check if the trip was confidential</div>
                        @php
                            $isConfidential = old('check', $header->is_confidential ?? 0);
                        @endphp
                        <input type="hidden" id="is_emergency" class="form-control form-input" name="is_emergency" value="{{ $header->is_emergency }}" />
                        <input type="hidden" id="is_nightdrive" class="form-control form-input" name="is_nightdrive" value="{{ $header->is_nightdrive }}" />
                        <div class="form-check" style="font-size: 15px;">
                            <input class="form-check-input" type="checkbox" name="is_confidential" value="1" id="confidentialCheck"
                                {{ $isConfidential == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="confidentialCheck">
                                Confidential
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <table class="table table-bordered" style="width: 100%;">
                            <colgroup>
                                <col style="width: 13.57%;"> 
                                <col style="width: 13.57%;">
                                <col style="width: 13.57%;">
                                <col style="width: 13.57%;">
                                <col style="width: 13.57%;">
                                <col style="width: 13.57%;">
                                <col style="width: 13.57%;">
                                <col style="width: 5%;"> 
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Vehicle Type</th>
                                    <th>Date Start</th>
                                    <th>Date End</th>
                                    <th>Starting Location</th>
                                    <th>Destination</th>
                                    <th>Trip Type</th>
                                    <th>Name of Passenger(s)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @for($i = 0; $i < count($details); $i++)
                                    <tr class="data-row" data-row-id="{{ $i }}">
                                        <input type="hidden" name="id[]" value="{{ $details[$i]->id ?? '' }}"/>
                                        <td>
                                            <select name="vehicle_type[{{ $i }}]" id="requested_vehicle_{{ $i }}" class="form-control vehicle-select2" style="width:150px; font-size:13px;">
                                                <option value="{{ $details[$i]->vehicle_type}}">{{ $details[$i]->vehicle_type}}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="start_datetime" class="form-control form-input small-datetime" name="start_datetime[]" style="width:125px; font-size:13px;" step="3600" value="{{ $details[$i]->departure_time }}" required />
                                        </td>
                                        <td>
                                            <input type="text" id="end_datetime" class="form-control form-input small-datetime" name="end_datetime[]" style="width:125px; font-size:13px;" value="{{ $details[$i]->end_time }}" step="3600" required />

                                        </td>
                                        <input type="hidden" name="requested_hrs[]" value="{{ $details[$i]->requested_hrs }}" />
                                        <td>
                                            <input type="text" class="form-control" name="destination_from[]" value="{{ $details[$i]->destination_from }}" style="width:150px; font-size:13px;" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="destination_to[]" value="{{ $details[$i]->destination_to }}" style="width:150px; font-size:13px;" />
                                        </td>
                                        <td>
                                            <select class="form-select form-input trip-select" name="trip_type[]" style="width:150px; font-size: 13px;">
                                                <option value="">Select</option>
                                                <option value="ONE WAY" {{ $details[$i]->trip_type == 'ONE WAY' ? 'selected' : '' }}>One Way</option>
                                                <option value="ROUND TRIP" {{ $details[$i]->trip_type == 'ROUND TRIP' ? 'selected' : '' }}>Round Trip</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control emp-select2" id="passengers-{{ $i }}" name="passengers[{{ $i }}][]" multiple style="width: 100%">
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger" type="button" onclick="removeRow({{ $i }})">
                                                <i class="fa-solid fa-circle-minus" style="color:white"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endfor
                                <tr id="add-btn-row">
                                    <td colspan="8" style="text-align: center">
                                        <button class="btn btn-primary" id="add-btn" type="button">
                                            <i class="fa-solid fa-circle-plus"></i> Add more
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <p style="font-size: 0.7rem;">Note: Submit the requisition (2) two days before the scheduled trip and must notify (1) one day before to requesting department if approve or reject.</p>

                <div style="display:flex; flex-direction:row; justify-content:end;">
                    <button class="btn btn-primary me-2 d-flex flex-row align-items-center" id="update-btn" type="submit">
                        <i class="fa-solid fa-paper-plane"></i>&nbsp;
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


    <div class="modal fade" id="emergencyTripModal" tabindex="-1" aria-labelledby="emergencyTripLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="emergencyTripLabel">Emergency Trip</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <p>
          Marked as <strong>emergency trip</strong> — your request is less than <strong>12 hours</strong>.  
          Kindly call your <strong>Manager</strong> for immediate approval.
        </p>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Okay</button>
      </div>
      
    </div>
  </div>
</div>



<script>
   function showEmergencyTripModal() {
    var myModal = new bootstrap.Modal(document.getElementById('emergencyTripModal'));
    myModal.show();
}



$(document).ready(function() {
    $('.vehicle-select2').select2({
        allowClear: true,
        ajax: {
        url: "{{ url('api/request_vehicles') }}",
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




   $('.emp-select2').select2({
    placeholder: "Search employee",
    allowClear: true,
    ajax: {
        url: "{{ url('api/get_employees') }}",
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

            

                var newRow = document.createElement('tr');
                var rowID = getNewRowID(rowCount);
                newRow.classList.add('data-row');
                newRow.setAttribute('data-row-id', rowID);

                newRow.innerHTML = `
                <input type="hidden" name="id[]" value=""/>
                <input type="hidden" name="row_id" value="${rowCount}"/>
                <td>
                 <select name="vehicle_type[{{ $i }}]" id="requested_vehicle_{{ $i }}" class="form-control vehicle-select2" style="width:100%;">
                 <option value="">Select Vehicle</option>
                            </select>
               </td>
                <td>
                    
                    <input type="text" class="form-control form-input small-datetime" name="start_datetime[]" style="width: 125px; font-size:13px;"/>  
                </td>
                <td>
                    <input type="text" class="form-control form-input small-datetime" name="end_datetime[]"  style="width: 125px; font-size:13px;"/>
                    <input type="hidden" class="form-control form-input" name="requested_hrs[]"   style="width:100%; font-size:13px;" min="1" required/>
                </td>
                <td>
                    <input type="text" class="form-control form-input" name="destination_from[]"   style="width:150px; font-size:13px;" required/>
                </td>
                <td>
                    <input type="text" class="form-control form-input" name="destination_to[]"   style="width:150px; font-size:13px;" required/>
                </td>
                <td>
                     <select class="form-select form-input trip-select" name="trip_type[]" style="width:150px; font-size: 13px;" required>
                                                    <option value="">Select a trip type</option>
                                                    <option value="ONE WAY">One Way</option>
                                                    <option value="ROUND TRIP">Round Trip</option>
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

          const startInput = newRow.querySelector('input[name="start_datetime[]"]');
        const endInput   = newRow.querySelector('input[name="end_datetime[]"]');

            [startInput, endInput].forEach(input => {
            if (input) {
                flatpickr(input, {
                    enableTime: true,
                    noCalendar: false,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: false,
                    minuteIncrement: 60,
                    onClose: () => computeRequestedHours(newRow)
                });
            }
        });

            $(`#passengers-${rowID}`).select2({
            placeholder: "Search Passengers",
            allowClear: true,
            ajax: {
                url: "{{ url('api/get_employees') }}",
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

         $('.vehicle-select2').select2({
            allowClear: true,
            ajax: {
                url: "{{ url('api/request_vehicles') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { term: params.term };
                },
                processResults: function (data) {
                    return { results: data.items };
                },
                cache: true
            }
        });

        bindHourComputationToRow(newRow);
        
            count++;
            if (count == 5) {
                $('#add-btn-row').hide();
            }
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
            url: "{{ url('request/update/' . $header->id) }}",
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
document.addEventListener('DOMContentLoaded', function () {


    window.computeRequestedHours = function (row) {
        const startInput = row.querySelector('input[name="start_datetime[]"]');
        const endInput = row.querySelector('input[name="end_datetime[]"]');
        const hrsInput = row.querySelector('input[name="requested_hrs[]"]');
        const emergencyInput = document.querySelector('input[name="is_emergency"]');
        const nightDriveInput = document.querySelector('input[name="is_nightdrive"]');

        if (!startInput || !endInput || !hrsInput) return;
        if (!startInput.value || !endInput.value) return;

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (isNaN(start) || isNaN(end)) return;

        const diffMs = end - start;
        if (diffMs < 0) {
            hrsInput.value = '';
            if (emergencyInput) emergencyInput.value = '';
            if (nightDriveInput) nightDriveInput.value = '';
            return;
        }

        const diffHrs = diffMs / (1000 * 60 * 60);
        hrsInput.value = diffHrs.toFixed(2);

        const startHour = start.getHours();
        const endHour = end.getHours();

        if (emergencyInput) emergencyInput.value = '';
        if (nightDriveInput) nightDriveInput.value = '';

        const today = new Date();
        const isToday =
            start.getFullYear() === today.getFullYear() &&
            start.getMonth() === today.getMonth() &&
            start.getDate() === today.getDate();


        if (diffHrs <= 12 && isToday) {
            if (emergencyInput) emergencyInput.value = 1;
            if (typeof showEmergencyTripModal === 'function') {
                showEmergencyTripModal();
            }
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
    };


    function initDateTimePicker(input, row, isEnd = false) {
        flatpickr(input, {
            enableTime: true,
            noCalendar: false,
            dateFormat: "Y-m-d H:i", 
            minuteIncrement: 60,
            onClose: function () {
                if (isEnd) {

                    const startInput = row.querySelector('input[name="start_datetime[]"]');
                    const endInput = row.querySelector('input[name="end_datetime[]"]');
                    if (startInput.value && endInput.value) {
                        computeRequestedHours(row);
                    }
                }
            }
        });
    }


    document.querySelectorAll('.data-row').forEach(row => {
        const startInput = row.querySelector('input[name="start_datetime[]"]');
        const endInput = row.querySelector('input[name="end_datetime[]"]');
        if (startInput) initDateTimePicker(startInput, row, false);
        if (endInput) initDateTimePicker(endInput, row, true); 
    });


    window.bindHourComputationToRow = function (row) {
        const startInput = row.querySelector('input[name="start_datetime[]"]');
        const endInput = row.querySelector('input[name="end_datetime[]"]');
        if (startInput) initDateTimePicker(startInput, row, false);
        if (endInput) initDateTimePicker(endInput, row, true);
    };
});
</script>

<script>
flatpickr("#start_datetime", {
  enableTime: true,
  noCalendar: false,
  dateFormat: "Y-m-d H:i", 
  time_24hr: false,
  minuteIncrement: 60
});
flatpickr("#end_datetime", {
  enableTime: true,
  noCalendar: false,
  dateFormat: "Y-m-d H:i", 
  time_24hr: false,
  minuteIncrement: 60
});
</script>
@endsection
