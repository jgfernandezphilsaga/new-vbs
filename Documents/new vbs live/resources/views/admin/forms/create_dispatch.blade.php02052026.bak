@extends('layouts.app')

@section('styles')
    
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-3 text-center">
                <h5 style="font-weight: 500">Create Vehicle Dispatch</h5>
            </div>
            <hr>

            <table class="table table-bordered" style="font-size:13px; width:100%;">
                <tr>
                    <td>
                        <label>Select Date</label>
                        <input type="date" id="selected-date" class="form-control form-input" name="selected-date" style="width:50%; font-size:13px;" required />
                        <button id="filter-btn" class="btn btn-primary mt-2">
                            <i class="fa fa-filter"></i> Search Date
                        </button>
                    </td>
                </tr>
            </table>

            <div class="col-12">
                <table data-order='[[ 0, "desc" ]]'  
                       id="dashboard-table" 
                       class="table table-hover table-bordered table-striped table-custom mb-3" 
                       style="table-layout:fixed; width:100%;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Ref #</th>
                            <th>Pickup Location</th>
                            <th>Destination</th>
                            <th>Department</th>
                            <th>End of Travel</th>
                            <th>Confidential</th>
                            <th>Emergency</th>
                            <th># Passengers</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        {{-- DataTable will insert rows here --}}
                    </tbody>
                </table>
            </div>

            <p style="font-size: 0.7rem;">
                Note: Submit the requisition (2) two days before the scheduled trip and must notify (1) one day before to requesting department if approve or reject.
            </p>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary me-2 d-flex align-items-center d-none" id="assign-btn" type="button">
                    <i class="fa-solid fa-car"></i> 
                    &nbsp;
                    <span id="submit-btn-text">Assign</span>
                </button>

                <button class="btn btn-danger" type="button" onclick="returnToDash()">
                    <i class="fa-solid fa-circle-xmark"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Dispatch Modal -->
<div class="modal fade" id="dispatchModal" tabindex="-1" aria-labelledby="dispatchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"> <!-- full width modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dispatchModalLabel">Dispatch Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Hello, world! This is a toast message.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
function showToast(message, type = 'primary') {
    let toastEl = document.getElementById('liveToast');
    let toastBody = document.getElementById('toastMessage');
    toastEl.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    let toast = new bootstrap.Toast(toastEl);
    toast.show();
}


function loadFilteredData(selectedDate) {
    if ($.fn.DataTable.isDataTable('#dashboard-table')) {
        $('#dashboard-table').DataTable().destroy();
    }

    $('#dashboard-table').DataTable({
        processing: true,
        serverSide: false,
        paging: false,     
        searching: false,   
        info: false,        
        ajax: {
            url: "{{ url('get_details') }}", 
            type: 'GET',
            data: function(d) {
                d.date = selectedDate; 
            },
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            {
                data: 'id_details',
                render: function(data) {
                    return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                },
                orderable: false,
                searchable: false
            },
            { data: 'id_header', visible: false },
            { 
                data: 'ref',
                render: function(data, type, row) {
                    return `<a href="{{ url('review') }}/${row.id_header}/${row.id_details}" target="_blank" class="text-primary">${data}</a>`;
                }
            },
            { data: 'destination_from' },
            { data: 'destination_to' },
            { data: 'requesting_dept' },  
            { data: 'end_time' },
            { data: 'is_confidential' },
            { data: 'is_emergency' },
            { data: 'passenger_count' }
        ],
        order: [[0, 'desc']],
        language: {
            emptyTable: "No records found.",
            processing: "Loading..."
        },
        scrollX: true,
        fixedColumns: true
    });
}


$(document).ready(function() {
    let urlParams = new URLSearchParams(window.location.search);
    let queryDate = urlParams.get('date');
    if (queryDate) {
        $('#selected-date').val(queryDate.split(' ')[0]);
        loadFilteredData(queryDate);
    }


    $('#filter-btn').on('click', function() {
        let selectedDate = $('#selected-date').val();
        if (selectedDate) {
            loadFilteredData(selectedDate);
        } else {
            alert('Please select a date.');
        }
    });
});


$(document).on('change', '.row-checkbox, #select-all', function () {
    let selected = [];
    $('.row-checkbox:checked').each(function () {
        selected.push($(this).val());
    });
    if (selected.length > 0) {
        $('#assign-btn').removeClass('d-none');
    } else {
        $('#assign-btn').addClass('d-none');
    }
});


$(document).on('click', '#assign-btn', function () {
    let selected = [];
    $('.row-checkbox:checked').each(function () {
        selected.push($(this).val());
    });

    if (selected.length === 0) {
        alert("No rows selected.");
        return;
    }

    $.ajax({
        url: "{{ route('selected_drivers') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            selected_ids: selected
        },
        success: function (response) {
            if (response.data && response.data.length > 0) {
                let selectOptions = '';
                let detailsInputs = '';
                let headerInputs = '';
                let request_reference = '';
                let requestor_name = '';
                let requestor_dept = '';
                let purpose = '';

                response.data.forEach(function(item) {
                    if (item.passengers) {
                        let passengers = item.passengers.split('/');
                        passengers.forEach(function(passenger) {
                            let [empId, empName] = passenger.split('|');
                            if (empId && empName) {
                                selectOptions += `<option value="${empName}">${empName}</option>`;
                            }
                        });

                        detailsInputs += `<input type="hidden" name="detail_ids[]" value="${item.id}">`;
                        headerInputs += `<input type="hidden" name="header_ids[]" value="${item.request_header_id}">`;
                        request_reference += `<input type="hidden" name="reference_no[]" value="${item.reference_id}">`;
                        requestor_name += `<input type="hidden" name="requestor_name[]" value="${item.user_fullname}">`;
                        requestor_dept += `<input type="hidden" name="requestor_dept[]" value="${item.requesting_dept}">`;
                        purpose += `<input type="hidden" name="orig_purpose[]" value="${item.purpose}">`;
                    }
                });

                let selectHtml = `
                    <form id="dispatchForm" method="POST" action="{{ route('dispatch.save_dispatch') }}">
                        @csrf
                        ${detailsInputs}
                        ${headerInputs}
                        ${request_reference}
                        ${requestor_name}
                        ${requestor_dept}
                        ${purpose}
                        <div class="mb-3">
                            <label for="detailsSelect" class="form-label">Select Passenger</label>
                            <select id="detailsSelect" name="passengers[]" class="form-select" multiple style="width: 100%;">
                                ${selectOptions}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="driverSelect" class="form-label">Select Driver</label>
                            <select id="driverSelect" name="driver_details[]" class="form-control" multiple style="width: 100%;"></select>
                        </div>
                        <div class="mb-3">
                            <label for="vehicleSelect" class="form-label">Select Vehicle</label>
                            <select id="vehicleSelect" name="vehicle_unit[]" class="form-control" multiple style="width: 100%;"></select>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <textarea id="purpose" name="purpose" class="form-control"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary me-2 d-flex flex-row align-items-center" id="dispatch-btn" type="button">
                                <i class="fa fa-paper-plane"></i>
                                <span class="ms-2">Dispatch</span>
                            </button>
                        </div>
                    </form>
                `;

                $('#dispatchModal .modal-body').html(selectHtml);
                $('#dispatchModal').modal('show');


                $('#detailsSelect').select2({ dropdownParent: $('#dispatchModal'), width: '100%' });
                $('#driverSelect').select2({
                    placeholder: "Search Driver",
                    allowClear: true,
                    dropdownParent: $('#dispatchModal'),
                    width: '100%',
                    ajax: {
                        url: "{{ url('api/get_drivers') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) { return { term: params.term }; },
                        processResults: function(data) { return { results: data.items }; },
                        cache: true
                    }
                });
                $('#vehicleSelect').select2({
                    placeholder: "Search Vehicle",
                    allowClear: true,
                    dropdownParent: $('#dispatchModal'),
                    width: '100%',
                    ajax: {
                        url: "{{ url('api/get_vehicles') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) { return { term: params.term }; },
                        processResults: function(data) { return { results: data.items }; },
                        cache: true
                    }
                });

            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert("Something went wrong.");
        }
    });
});

$(document).on('click', '#dispatch-btn', function(e) {
    e.preventDefault();
    let $form = $('#dispatchForm');
    let $btn = $(this);

    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Dispatching...');

    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: new FormData($form[0]),
        processData: false,
        contentType: false,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        success: function(response) {
            $('#dispatchModal').modal('hide');
            showToast('Dispatch created successfully!', 'success');

            let selectedDate = $('#selected-date').val();
            if (selectedDate) loadFilteredData(selectedDate);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert("Dispatch failed. Check console for errors.");
        },
        complete: function() {
            $btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> <span class="ms-2">Dispatch</span>');
        }
    });
});
</script>

@endsection
