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
                            <div class="card dash-saved-card counter-card" data-status="1012">
                                <div class="card-title p-2 m-2">DRAFT</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;" id="draft"></h3>
                                        <i class="fa-solid fa-floppy-disk fa-5x card-icon"></i>
                                    </div>    
                                </div>
                            </div>
                        </div>
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
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;" id="completed_count"></h1>
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
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;" id="disapproved_count"></h1>
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
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;" id="hold_count"></h1>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div style="display:flex; flex-direction:row; justify-content:space-between; align-items:center; padding-bottom:12px;">
                        <div class="d-flex flex-row">
                            <h5>
                                Requests List
                            </h5>
                        </div>
                        <div class="d-flex flex-row">  
                            <div class="dropdown me-2" style="font-size: 14px; height: 100%">
                                <select name="statuses[]" id="status-select" class="form-select" style="font-size: 13px; height: 38.5px;" multiple>
                                @php
                                    $checkedStatuses = explode(',', request('statuses'));
                                @endphp
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" 
                                        {{ in_array($status->id, $checkedStatuses) ? 'selected' : '' }}>
                                        {{ $status->status }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            <input type="text" onkeydown="return false" class="form-control me-2" style="font-size: 12px; width: 17vw; min-width: 2vw;" id="date-range" name="date-range" value="" placeholder="Pick date range" autocomplete="off"/>
                            <button type="button" class="btn btn-secondary ms-2" id="reset-btn"><i class="fa-solid fa-arrow-rotate-left"></i> Reset</button></button>
                            @if(session('user_role') === 'dept_secretary')
                                <a href="{{ route('create.request') }}" class="btn btn-primary ms-2" style="white-space:nowrap"><i class="fa-solid fa-circle-plus"></i> Create Request</a>
                            @endif
                        </div>    
                    </div>
                     <table data-order='[[ 0, "desc" ]]'  id="dashboard-table" class="table table-hover table-bordered table-striped table-custom mb-0" style="border-top-left-radius: 8px; table-layout:fixed">
                        <thead>
                            <tr>    
                                <th>#</th>
                                <th>ID</th>
                                <th>Requesting Dept</th>
                                <th>Requested By</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Requested On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody style="width: 100%;" id="table-body">
  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.components.modal')

    

<script>
$(document).ready(function () {
    let table = $('#dashboard-table').DataTable({
        ajax: {
            url: "{{ url('dashboard_tbl') }}",
            dataSrc: function (json) {

                updateSummaryCards(json.summary);
                return json.data || [];
            }
        },
        columns: [
            { data: "id" },
            { data: "reference_no" },
            { data: "requesting_dept" },
            { data: "user_fullname" },
            { data: "purpose" },
            { data: "status_html" }, 
            { data: "created_at" },  
            { data: "actions" },
            { data: "status", visible: false } 
        ],
        columnDefs: [
            { targets: [5, 7], orderable: false, searchable: false }
        ]
    });


    table.on('draw', function () {
        recalculateCardsFromTable();
    });


    $.fn.dataTable.ext.search.push(function (settings, data) {
        let selectedStatuses = $('#status-select').val(); 
        let rowStatusId = data[8]; 


        if (selectedStatuses && selectedStatuses.length > 0 && !selectedStatuses.includes(rowStatusId)) {
            return false;
        }


        let dateRange = $("#date-range").val();
        if (dateRange) {
            let parts = dateRange.split(" - ");
            if (parts.length === 2) {
                let start = new Date(parts[0] + " 00:00:00");
                let end   = new Date(parts[1] + " 23:59:59");
                let rowDate = new Date(data[6]);

                if (rowDate < start || rowDate > end) {
                    return false;
                }
            }
        }

        return true;
    });


    $(document).on("change", "#status-select", function () {
        table.draw();
    });


    $("#date-range").on("change", function () {
        table.draw();
    });


    $('#date-range').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear' }
    });

    $('#date-range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        table.draw();
    });

    $('#date-range').on('cancel.daterangepicker', function() {
        $(this).val('');
        table.draw();
    });


    $(document).on("click", ".counter-card", function () {
        let statusId = $(this).data("status"); 

        if (statusId) {
            table.column(8).search("^" + statusId + "$", true, false).draw();
        } else {
            table.column(8).search("").draw();
        }
    });


    $("#reset-btn").on("click", function () {
        table.search('').columns().search('');
        table.order([[0, 'asc']]).draw();
        $("#status-select").val([]).trigger('change');
        $("#date-range").val('');
        table.draw();
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
});
</script>


<script>
$(document).ready(function() {
    $('#status-select').select2({
        placeholder: "Status Options",
        allowClear: true,
        width: 'resolve'
    });
});
</script>





<!-- <script>
async function markDeparted() {
  const res = await fetch('/api/run-minute-job', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  });
  const data = await res.json();
  console.log(`Updated ${data.rows_updated} rows`);
}


markDeparted();
setInterval(markDeparted, 60_000);
</script> -->


@endsection
