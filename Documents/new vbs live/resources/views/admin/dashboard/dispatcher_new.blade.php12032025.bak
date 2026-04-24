@extends('layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="row cards-row pt-0">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body row">
                    <div class="card-title d-flex flex-row justify-content-between">
                        <div class="d-flex align-items-baseline" style="margin-bottom: 1rem">
                            <h5 style="vertical-align: middle; height:100%; position:relative; top: 5px; line-height: 1.5" >Summary</h5>
                        </div>
                    </div>
                    <div class="row justify-content-evenly m-0">
                        <!-- <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card dash-drafted-card counter-card" data-status="1">
                                <div class="card-title p-2 m-0">> DRAFTED</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;"> $drafted_count </h3>
                                        <i class="fa-solid fa-file-lines fa-5x card-icon"></i>
                                    </div>    
                                </div>
                            </div>
                        </div> -->
                        
                        <div class="col-md-2 col-sm-6 card-container p-0">
                            <a target="_blank">
                                <div class="card counter-card" style=" background-color: #f13513e3;" data-status="1007">
                                    <div class="card-title p-2 m-2">For Dispatch</div>
                                    <div class="card-body">
                                        <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                            <h1 style="font-weight:bold; margin:0px; padding:0px;" id="posted_count"></h1>
                                            <i class="fa-solid fa-clipboard fa-5x card-icon"></i>
                                            <!-- <i class="fa-solid fa-clock fa-5x card-icon"></i> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card dash-approved-card counter-card" data-status="1010">
                                <div class="card-title p-2 m-2">COMPLETED</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;" id="completed_count"></h3>
                                        <!-- <i class="fa-solid fa-circle-check fa-5x card-icon" ></i> -->
                                        <i class="fa-solid fa-square-check fa-5x card-icon"></i>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card counter-card" style="background-color: #121212ff;" data-status="1013">
                                <div class="card-title p-2 m-2">CANCEL</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;" id="disapproved_count"></h3>
                                        <i class="fa-solid fa-list-check fa-5x card-icon"></i>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card dash-hold-card counter-card" data-status="5">
                                <div class="card-title p-2 m-2">HOLD</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;" id="hold_count"></h3>
                                        <i class="fa-solid fa-square-minus fa-5x card-icon"></i>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="row">
    <div class="row mb-3">
    <div class="col-md-3">
        <input type="text" onkeydown="return false" class="form-control me-2" style="font-size: 12px; width: 17vw; min-width: 2vw;" id="date-range" name="date-range" value="" placeholder="Pick date range" autocomplete="off"/>
    </div>
    <div class="dropdown me-2 col-md-3" style="font-size: 14px; height: 100%">
                                <select name="department[]" id="dept-select" class="form-select" style="font-size: 13px; height: 38.5px;" multiple>
                                </select>
    </div>
    <div class="col-md-3 d-flex align-items-end gap-2">
    <button id="reset-btn" class="btn btn-secondary">
        <i class="fa fa-undo"></i> Clear
    </button>
    <button id="create-btn" class="btn btn-primary">
        <i class="fa fa-calendar"></i> <span>Create Dispatch</span>
    </button>
    </div>
    </div>
</div>
    <div class="col-md-12">
    <table data-order='[[ 0, "desc" ]]'  id="dashboard-table" class="table table-hover table-bordered table-striped table-custom mb-0" style="border-top-left-radius: 8px; table-layout:fixed">
         <thead>
        <tr>
            <th>Requesting Dept</th>
            <th>Reference_no</th>
            <th>Requestor</th>
            <th>Date of Departure</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Trip Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>


        <tbody style="width: 100%;" id="table-body">
            {{-- Empty on page load --}}
        </tbody>
    </table>
</div>
</div>
    <!-- Modal for viewing request details -->
    @include('admin.components.dispatch_view')
    
    
<script>
    document.getElementById("create-btn").addEventListener("click", function() {
        window.location.href = "{{ route('dispatch.create') }}";
    });
</script>

<script>

function showAlert(message, type = 'success') {
    const alertBox = $('#statusAlert');

    alertBox
        .removeClass('d-none alert-success alert-danger')
        .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
        .text(message);

    setTimeout(() => {
        alertBox.addClass('d-none');
    }, 4000);
}


function updateRequest(id, status) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
       url: "{{ url('request/update-status') }}",
        method: 'POST',
        data: { id: id, status: status },

        success: function (response) {
            console.log('Success:', response);
            showAlert(response.message, 'success');

            setTimeout(() => {
                window.location.reload(true); 
            }, 1000);
        },

        error: function (xhr) {
            let errorMessage = xhr.status === 422
                ? JSON.parse(xhr.responseText).message
                : `Error ${xhr.status}: ${xhr.statusText}`;

            showAlert(errorMessage, 'danger');

            setTimeout(() => {
                window.location.reload(true);
            }, 1500);
        }
    });
}

$(document).on('click', '.btn-update-request', function () {
    const id     = $(this).data('id');     
    const status = $(this).data('status'); 
    updateRequest(id, status);
});

</script>
<script>
$('#filter-btn').on('click', function() {
    let startDate = $('#start-date').val();
    let endDate = $('#end-date').val();

    if (startDate && endDate) {
        loadFilteredData(startDate, endDate);
    } else {
        alert('Please select both start and end dates.');
    }
});

$('#reset-btn').on('click', function () {
    $('#date-range').val('');
    startDate = null;
    endDate = null;
    table.column(8).search('').draw();
    table.draw();
    recalculateCardsFromTable();
});


</script>

<script>
let table;
let startDate = null;
let endDate = null;

$(document).ready(function () {

    table = $('#dashboard-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ url('request_today') }}",
            type: "GET",
            dataSrc: function (json) {
                updateSummaryCards(json.summary);
                return json.data || [];
            }
        },
        columns: [
            { data: "requesting_dept" }, 
            { data: "reference_no" },
            { data: "user_fullname" },
            {
                data: "departure_time",
                render: function (data) {
                    return data ? new Date(data).toLocaleString() : '';
                }
            },
            { data: "origin" },
            { data: "destination_to" },
            { data: "trip_type" },
            { data: "status_html" },
            { data: "actions" },
            { data: "status", visible: false }
        ],
        columnDefs: [
            { targets: [7, 8], orderable: false, searchable: false }
        ],
        order: [[2, "desc"]],
        language: {
            emptyTable: "No records found.",
            processing: "Loading..."
        },
        initComplete: function () {

            let deptColumn = this.api().column(0);
            let select = $('#dept-select');


            select.find('option:not(:first)').remove();

            let departments = [];
            deptColumn.data().unique().sort().each(function (d) {
                if (d && !departments.includes(d)) {
                    departments.push(d);
                    select.append('<option value="' + d + '">' + d + '</option>');
                }
            });


            select.trigger('change.select2');
        }
    });


    table.on('draw', function () {
        recalculateCardsFromTable();
    });


    $(document).on("click", ".counter-card", function () {
        let statusId = $(this).data("status");
        if (statusId) {
            table.column(9).search("^" + statusId + "$", true, false).draw();
        } else {
            table.column(9).search("").draw();
        }
    });


    $('#date-range').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear' }
    });

    $('#date-range').on('apply.daterangepicker', function(ev, picker) {
        startDate = picker.startDate.startOf('day');
        endDate = picker.endDate.endOf('day');
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        table.draw(); 
    });

    $('#date-range').on('cancel.daterangepicker', function() {
        $(this).val('');
        startDate = null;
        endDate = null;
        table.draw(); 
    });


    $.fn.dataTable.ext.search.push(function (settings, data) {
        let dateStr = data[3];
        if (!dateStr) return true;
        let cellDate = new Date(dateStr);
        if (!startDate || !endDate) return true;
        return cellDate >= startDate && cellDate <= endDate;
    });


    $('#dept-select').on('change', function () {
    let val = $(this).val();

    if (val && val !== "") {
        table.column(0).search('^' + val + '$', true, false).draw();
    } else {
        table.search('').columns().search('').draw();
    }
});

    $('#dept-select').on('select2:clear', function () {
    table.column(0).search('').draw();
});

    $('#reset-btn').on('click', function () {
        $('#date-range').val('');
        $('#dept-select').val('').trigger('change');
        startDate = null;
        endDate = null;
        table.search('').columns().search('').draw();
    });
});



function updateSummaryCards(summary) {
    if (!summary) return;
    $('#posted_count').text(summary.posted_count ?? 0);
    $('#draft').text(summary.draft ?? 0);
    $('#completed_count').text(summary.completed_count ?? 0);
    $('#disapproved_count').text(summary.disapproved_count ?? 0);
    $('#hold_count').text(summary.hold_count ?? 0);
}


function recalculateCardsFromTable() {
    let posted = 0, draft = 0, completed = 0, disapproved = 0, hold = 0;

    table.rows({ filter: 'applied' }).every(function () {
        let status = this.data().status;
        switch (status) {
            case '1007': posted++; break;
            case '1012': draft++; break;   
            case '1010': completed++; break;
            case '1013': disapproved++; break;
            case '5':    hold++; break;
        }
    });

    $('#posted_count').text(posted);
    $('#draft').text(draft);
    $('#completed_count').text(completed);
    $('#disapproved_count').text(disapproved);
    $('#hold_count').text(hold);
}
</script>

<script>
$(document).ready(function() {
    $('#dept-select').select2({
        placeholder: "Dept Options",
        allowClear: true,
        width: 'resolve'
    });
});
</script>

    
<script>
document.addEventListener('click', function(e) {
    const button = e.target.closest('.dispatch-btn');
    if (!button) return; 

    e.preventDefault();

    const date = button.getAttribute('data-date');


    console.log('Dispatch clicked:', {date }); 

    window.location.href = "{{ route('dispatch.create') }}" +
        "?date=" + encodeURIComponent(date);
});
</script>

@endsection
