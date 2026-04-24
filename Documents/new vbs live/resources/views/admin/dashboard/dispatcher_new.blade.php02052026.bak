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
                                <div class="card counter-card" style=" background-color: #ff6f3c;" data-status="1011">
                                    <div class="card-title p-2 m-2">PENDING</div>
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
        <label for="start-date" class="form-label fw-bold">Start Date</label>
        <input type="date" id="start-date" name="start-date" class="form-control">
    </div>
    <div class="col-md-3">
        <label for="end-date" class="form-label fw-bold">End Date</label>
        <input type="date" id="end-date" name="end-date" class="form-control">
    </div>
    <div class="col-md-6 d-flex align-items-end gap-2">
    <button id="filter-btn" class="btn btn-primary">
        <i class="fa fa-filter"></i> Search Date
    </button>
    <button id="reset-btn" class="btn btn-secondary">
        <i class="fa fa-undo"></i> Clear
    </button>
    <button id="create-btn" class="btn btn-primary">
        <i class="fa fa-calendar"></i> <span>Create Dispatch</span>
    </button>
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

$('#reset-btn').on('click', function() {
    $('#start-date').val('');
    $('#end-date').val('');
    loadAllData();
});


</script>

<script>
let table;

$(document).ready(function () {
    table = $('#dashboard-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ url('request_today') }}",
            type: "GET",
            data: function (d) {
                if (window.startDate && window.endDate) {
                    d.dateRange = window.startDate + '|' + window.endDate;
                }
            },
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
            { data: "destination_to" },
            { data: "trip_type" },
            { data: "status_html" },
            { data: "actions" },
            { data: "status", visible: false }
        ],
        columnDefs: [
            { targets: [6, 7], orderable: false, searchable: false }
        ],
        order: [[2, "desc"]],
        language: {
            emptyTable: "No records found.",
            processing: "Loading..."
        }
    });

    // ✅ Recalculate cards whenever table is redrawn (e.g., filter, search, load)
    table.on('draw', function () {
        recalculateCardsFromTable();
    });

    // ✅ Filter when clicking summary cards
    $(document).on("click", ".counter-card", function () {
        let statusId = $(this).data("status");
        if (statusId) {
            table.column(8).search("^" + statusId + "$", true, false).draw();
        } else {
            table.column(8).search("").draw();
        }
    });
});


// ✅ Filtered data by date
function loadFilteredData(startDate, endDate) {
    window.startDate = startDate;
    window.endDate = endDate;
    table.ajax.url("{{ url('requests/filter') }}").load();
}

// ✅ Reset to today
function loadTodayData() {
    window.startDate = null;
    window.endDate = null;
    table.ajax.url("{{ url('request_today') }}").load();
}

// ✅ Initial summary cards from server response
function updateSummaryCards(summary) {
    if (!summary) return;
    $('#posted_count').text(summary.posted_count ?? 0);
    $('#draft').text(summary.draft ?? 0);
    $('#completed_count').text(summary.completed_count ?? 0);
    $('#disapproved_count').text(summary.disapproved_count ?? 0);
    $('#hold_count').text(summary.hold_count ?? 0);
}

// ✅ Recalculate cards based on filtered table rows
function recalculateCardsFromTable() {
    let posted = 0;
    let draft = 0;
    let completed = 0;
    let disapproved = 0;
    let hold = 0;

    table.rows({ filter: 'applied' }).every(function () {
        let status = this.data().status;
        switch (status) {
            case '1011': posted++; break;
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




@endsection
