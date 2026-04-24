@extends('layouts.app')

@section('styles')
<style>
.small-datetime {
  width: 180px;   
  padding: 4px;   
  font-size: 14px;
}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="request-form" action="{{ route('store.request') }}" method="POST">
            @csrf
            <div class="card-body"><!-- you can also remove this if you want full plain layout -->
                <div class="row" style="text-align:center">
                    <h5 style="font-weight: 500">Create New Vehicle Request</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-8 mb-2">
                        <div class="form-group">
                            <label class="form-check-label" style="font-size: 15px;">
                                Purpose
                            </label>
                            <textarea class="form-control form-input" name="purpose"></textarea>
                        </div>
                        </br>
                        <div class="form-label small" style="font-size: 12px;">Check if the trip was confidential</div>
                        <div class="form-check" style="font-size: 15px;">
                            <input class="form-check-input" type="checkbox" name="is_confidential" value="1" id="confidentialCheck">
                            <label class="form-check-label" for="confidentialCheck">
                                Confidential
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
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
                            <th>Vehicle Type</th>
                            <th>Date Start</th>
                            <th>Date End</th>
                            <th>Starting Location</th>
                            <th>Destination</th>
                            <th>Trip Type</th>
                            <th>Name of Passenger(s)</th>
                            <th></th>
                        </thead>
                        <tbody id="table-body">
                            @for($i = 0; $i < 1; $i++)
                                <tr class="data-row" data-row-id="{{ $i }}">
                                    <input type="hidden" id="is_emergency" name="is_emergency" value=""/>
                                    <input type="hidden" id="is_nightdrive" name="is_nightdrive" value=""/>
                                    <td>
                                        <select name="vehicle_type[{{ $i }}]" id="requested_vehicle_{{ $i }}" class="form-control vehicle-select2" style="width:150px; font-size:13px;">
                                            <option value="">Select vehicle</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="row_id" value="{{ $i }}"/>
                                        <input type="text" id="start_datetime" class="form-control form-input small-datetime" style="width:125px; font-size:13px;" name="start_datetime[]" step="3600" required />
                                    </td>
                                    <td>
                                        <input type="text" id="end_datetime" class="form-control form-input small-datetime" style="width:125px; font-size:13px;" name="end_datetime[]" step="3600" required />
                                        <input type="hidden" id="requested_hrs" name="requested_hrs[]" value=""/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-input" name="destination_from[]" style="width:150px; font-size:13px;" required/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-input" name="destination_to[]" style="width:150px; font-size:13px;" required/>
                                    </td>
                                    <td id="trip-type-col">
                                        <select class="form-select form-input trip-select" name="trip_type[]" style="width:150px; font-size: 13px;" required>
                                            <option value="">Select a trip type</option>
                                            <option value="ONE WAY">One Way</option>
                                            <option value="ROUND TRIP">Round Trip</option>
                                        </select>    
                                    </td>
                                    <td>
                                        <select class="form-control emp-select2" id="passengers-{{ $i }}" name="passengers[{{ $i }}][]" multiple required style="width: 100%"></select>
                                    </td>
                                    <td id="remove-btn-col">
                                        @php
                                            $style = 'style=opacity:0%';
                                            $attributes = 'disabled';
                                            if($i > 0) {
                                                $style = 'style=opacity:100%';  
                                                $attributes = '';
                                            }
                                        @endphp
                                        <button class="btn btn-danger" type="button" onclick="removeRow({{ $i }})" {{ $style }} {{ $attributes }}>
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

            <p style="font-size: 0.7rem;">Note: Submit the requisition atleast (2) two days before the scheduled day trip and must notify (1) one day before to requesting department if approve or reject.</p>

            <div style="display:flex; flex-direction:row; justify-content:end;">
                <button class="btn btn-primary me-2 d-flex flex-row align-items-center" id="submit-btn" type="button">
                    <i class="fa-solid fa-paper-plane"></i> 
                    &nbsp;
                    <span id="submit-btn-text">Submit</span>
                </button>
                <button class="btn btn-danger" type="button" onclick="returnToDash()">
                    <i class="fa-solid fa-circle-xmark"></i> Cancel
                </button>
            </div>
        </form>

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

@if(Session::get('data'))
    @php(dd(Session::get('selectValues'), Session::get('selectRow')))
@endif

  <script>
    function showEmergencyTripModal() {
    var myModal = new bootstrap.Modal(document.getElementById('emergencyTripModal'));
    myModal.show();
}



    $(document).ready(function() {
    $('#requested_vehicle').select2({
        allowClear: true
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

   </script>

<script>

    let count = 1;

    function removeRow(rowId) {
    const row = document.querySelector(`[data-row-id="${rowId}"]`);
    if (row) {
        row.remove();
        count--;
        if (count < 5) {
            $('#add-btn-row').show();
        }
    } else {
        console.warn(`Row with ID ${rowId} not found.`);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('add-btn');
    addBtn.addEventListener('click', function () {
        const rowID = getNewRowID(count);
        const newRow = document.createElement('tr');
        newRow.classList.add('data-row');
        newRow.setAttribute('data-row-id', rowID);
        newRow.innerHTML = `
            <td>
                <select name="vehicle_type[]" id="requested_vehicle_${rowID}" class="form-control vehicle-select2" style="width:150px; font-size:13px;">
                    <option value="">Select vehicle</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-input small-datetime" name="start_datetime[]" style="width:125px; font-size:13px;"/>   
            </td>
            <td>
                <input type="text" class="form-control form-input small-datetime" name="end_datetime[]"  style="width:125px; font-size:13px;"/>
                <input type="hidden" id="requested_hrs" class="form-control form-input " name="requested_hrs[]"  value="" style="width:100%; font-size:13px;"/>
            </td>
            <td>
                <input type="text" class="form-control form-input" name="destination_from[]" value="" style="width:150px; font-size:13px;" required/>
            </td>
            <td>
                 <input type="text" class="form-control form-input" name="destination_to[]" value="" style="width:150px; font-size:13px;" required/>
            </td>
            <td>
                <select class="form-select form-input trip-select" name="trip_type[]" style="width:150px; font-size:13px;" required>
                                                    <option value="">Select a trip type</option>
                                                    <option value="ONE WAY">One Way</option>
                                                    <option value="ROUND TRIP">Round Trip</option>
                                                </select>   
            </td>
            <td>
                <select class="form-control emp-select2" id="passengers-${rowID}" name="passengers[${rowID}][]" multiple style="width:100%" required></select>
            </td>
            <td>
                <button class="btn btn-danger" type="button" onclick="removeRow(${rowID})">
                    <i class="fa-solid fa-circle-minus" style="color:white"></i>
                </button>
            </td>
        `;

        document.getElementById('add-btn-row').before(newRow);

    
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
                    return { term: params.term };
                },
                processResults: function (data) {
                    return { results: data.items };
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
        if (count === 5) {
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

    function returnToDash() {
    window.location.href = "{{ url('/') }}";
}

document.addEventListener("DOMContentLoaded", function () {
    const submitBtn = document.getElementById('submit-btn'); 
    const tableBody = $('#table-id');

    submitBtn.addEventListener('click', function (event) {
        $('#submit-btn').prop('disabled', true);
        $('#submit-btn-text').text('Submitting...');

        tableBody.children()
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

        tableBody.children()
        .not('#add-btn-row')
        .each(function(index, row) {
            let rowSelect2 = $(row).find('.emp-select2');
            let selectVal = rowSelect2.val();

            if (Array.isArray(selectVal)) {
                let lastVal = selectVal[selectVal.length - 1];
                if (lastVal === 'null' || lastVal === undefined) {
                    rowSelect2.val(null).trigger('change');
                }
            } else if (selectVal === 'null' || selectVal === undefined) {
                rowSelect2.val(null).trigger('change');
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            }
        });

        $.ajax({
            url: "{{ url('request/store') }}",
            method : 'POST',
            data: requestData,
            processData: false,
            contentType: false,
            success: function(response){
                const protocol = window.location.protocol;
                const hostname = window.location.hostname;
                const port = window.location.port ? `${window.location.port}` : '';

                if(response.status != 'error') {
                    window.location.href = `${protocol}//${hostname}:${port}${response.route}`;
                } else {
                    if(response.validation == 'initial') { 
                        let reqFormElement = $('#request-form');
                        let listenerType = response.input == 'purpose' ? 'keydown' : 'change';

                        let failedInputs = response.input;
                        failedInputs.forEach(function(element) {
                            reqFormElement.find(`[name="${element}"]`).css({
                                'border-color': '#f1aeb5',
                                'background-color': '#f8d7da'
                            });

                            reqFormElement.on(listenerType, `[name="${element}"]`, function() {
                                $(this).css({
                                    'border-color': '#dee2e6',
                                    'background-color': '#ffffff'
                                });
                            });
                        });
                        
                    } else if (response.validation == 'latter') {
    let failedRows = response.row;
    let failedInputs = response.input;

    failedRows.forEach(function(element, failedIndex) {
        tableBody.children()
            .not('#add-btn-row')
            .each(function(index, row) {
                if (index == failedRows[failedIndex]) {

                    if (failedInputs[failedIndex] == 'passengers') {
                        let selectInput = $(row).find(`[name="${failedInputs[failedIndex]}[${failedRows[failedIndex]}][]"]`);
                        let spanSelect = selectInput.siblings().find('.select2-selection--multiple');

                        spanSelect.addClass('form-input').css({
                            'border-color': '#f1aeb5',
                            'background-color': '#f8d7da'
                        });

                        selectInput.on('select2:select', function() {
                            spanSelect.css({
                                'border-color': '#dee2e6',
                                'background-color': '#ffffff'
                            });
                        });

                    } else if (
                        failedInputs[failedIndex] == 'start_datetime' || 
                        failedInputs[failedIndex] == 'end_datetime'
                    ) {
     
                        $(row).find(`[name="${failedInputs[failedIndex]}[]"]`).css({
                            'border-color': '#f1aeb5',
                            'background-color': '#f8d7da'
                        }).on('change', function() {
                            $(this).css({
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
}

                    let errorMessage = response.message;
                    triggerToast(response.status, errorMessage);
                }      
            },
            error: function(data) {
                triggerToast('error', data.responseText);
            }
            
        });

        $('#submit-btn').prop('disabled', false);
        $('#submit-btn-text').text('Submit');
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
document.addEventListener('DOMContentLoaded', function () {
   
    document.querySelectorAll('#table-id input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {

            let qtyInput = this.closest('tr').querySelector('input[type="number"]');
            
            if (this.checked) {
                qtyInput.disabled = false;
                qtyInput.focus();
            } else {
                qtyInput.disabled = true;
                qtyInput.value = 0;
            }
        });


        let qtyInput = checkbox.closest('tr').querySelector('input[type="number"]');
        qtyInput.disabled = true;
    });
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
