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
                            <h5 style="vertical-align: middle; height:100%; position:relative; top: 5px; line-height: 1.5"  >Summary</h5>
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
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;">{{ $saved_count }}</h3>
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
                                            <h1 style="font-weight:bold; margin:0px; padding:0px;">{{ $posted_count }}</h1>
                                            <i class="fa-solid fa-clipboard fa-5x card-icon"></i>
                                            <!-- <i class="fa-solid fa-clock fa-5x card-icon"></i> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card dash-approved-card counter-card" data-status="3">
                                <div class="card-title p-2 m-2">APPROVED</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;">{{ $approved_count }}</h3>
                                        <!-- <i class="fa-solid fa-circle-check fa-5x card-icon" ></i> -->
                                        <i class="fa-solid fa-square-check fa-5x card-icon"></i>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card counter-card" style="background-color: #c24d2c;" data-status="1010">
                                <div class="card-title p-2 m-2">APPROVED(CLOSED)</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;">{{ $completed_count }}</h3>
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
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;">{{ $hold_count }}</h3>
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
                            @if(session('user_role') !== 'dept_secretary')
                                <select class="form-select form-select-lg me-2" style="font-size: 12px; width: 10vw; min-width: 2vw;" id="filter-department" name="filter_department" aria-label="Large select example" style="font-size: 13px;">
                                    <option value="" selected> Select department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <select class="form-select form-select-lg me-2" style="font-size: 12px; width: 10vw; min-width: 2vw;" id="filter-requested-vehicle" name="filter_requested_vehicle" aria-label="Large select example" style="font-size: 13px;">
                                <option value="" selected> Select a vehicle</option>
                                @foreach($vehicle_types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            <div class="dropdown me-2" style="font-size: 14px; height: 100%">
                                <button class="btn dropdown-toggle"  style="font-size: 12px; height: 38.5px; background-color: #fff; border-width: 1px; border-color: #dee2e6;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="status-selected-text">Status Options</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-checkboxes" style="padding: 5px 10px;"  aria-labelledby="dropdownMenuButton">
                                    @foreach($statuses as $status)
                                        @php( $status_name = strtolower($status->status) )
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="statuses[]" value="{{ $status->id }}" id="checkbox-{{ $status_name }}">
                                                <label class="form-check-label filter-status-btn-label" style="font-size: 13px;" for="checkbox-{{ $status_name }}">
                                                    {{ $status->status }}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="text" onkeydown="return false" class="form-control me-2" style="font-size: 12px; width: 17vw; min-width: 2vw;" id="date-range" name="date-range" value="" placeholder="Pick date range" autocomplete="off"/>
                            <!-- <label>Input date range</label> -->
                            <!-- <input type="datetime-local" class="form-control" style="font-size: 13px; width: 17vw" placeholder="asdf"/> -->
                            <button type="button" class="btn btn-success ms-2" id="filter-btn"><i class="fa-solid fa-filter"></i> Filter
                            <button type="button" class="btn btn-secondary ms-2" id="reset-btn"><i class="fa-solid fa-arrow-rotate-left"></i> Reset</button></button>
                            @if(session('user_role') === 'dept_secretary')
                                <a href="{{ route('create.request') }}" class="btn btn-primary ms-2" style="white-space:nowrap"><i class="fa-solid fa-circle-plus"></i> Create Request</a>
                            @endif
                        </div>    
                    </div>
                    <table data-order='[[ 0, "desc" ]]'  id="dashboard-table" class="table table-hover table-bordered table-striped table-custom mb-0" style="border-top-left-radius: 8px; table-layout:fixed">
                        <thead>
                            <tr>    
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">#</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">ID</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">Requesting Dept</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">Requested By</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">Vehicle</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">Purpose</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">Status</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">Requested On</th>
                                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request_headers as $header)
                                <tr>
                                    <td style="text-align: center">{{ $header->id }}</td>
                                    <td style="text-align: center">{{ $header->reference_id }}
                                        {{ $header->reference_id }}
                                        @if($header->is_emergency == 1)
                                            <span style="font-size:10px; color:red">EMERGENCY</span>@endif
                                    </td>
                                    <td style="text-align: center">{{ $header->requesting_dept }}</td>
                                    <!-- <td style="text-align: center">{{ $header->user_id }}</td> -->
                                    <td style="text-align: center">{{ $header->user_fullname }}</td>
                                    <td style="text-align: center">{{ $header->requested_vehicle }}</td>
                                    <td style="text-align: center; max-width: 20%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $header->purpose }}
                                    </td>
                                    <td style="text-align:center">
                                        @if($header->status == 1)
                                            <span class="badge status-saved m-0" style="width: 100%"> SAVED </span> <!-- UPDATE CSS -->
                                        @elseif($header->status == 2)
                                            <span class="badge status-pending m-0" style="width: 100%"> PENDING </span>
                                        @elseif($header->status == 3)
                                            <span class="badge status-approved m-0" style="width: 100%"> APPROVED </span>
                                        @elseif($header->status == 4)
                                            <span class="badge status-completed m-0" style="width: 100%"> COMPLETED </span>
                                        @elseif($header->status == 5)
                                            <span class="badge status-hold m-0" style="width: 100%"> HOLD </span>
                                        @elseif($header->status == 6)
                                            <span class="badge status-disapproved m-0" style="width: 100%"> DISAPPROVED </span>
                                        @elseif($header->status == 1008)
                                            <span class="badge status-fully-approved m-0" style="width: 100%"> FULLY APPROVED </span>
                                            @elseif($header->status == 1006)
                                            <span class="badge status-in-progress m-0" style="width: 100%"> IN-PROGRESS </span>
                                            @elseif($header->status == 1007)
                                            <span class="badge status-partially-approved m-0" style="width: 100%"> PARTIALLY APPROVED </span>
                                            @elseif($header->status == 1009)
                                            <span class="badge status-approved-open m-0" style="width: 100%"> APRVD(OPEN) </span>
                                            @elseif($header->status == 1010)
                                            <span class="badge status-approved-closed m-0" style="width: 100%"> APRVD(CLOSED) </span>
                                            @elseif($header->status == 1011)
                                            <span class="badge status-pending m-0" style="width: 100%"> PENDING </span>
                                            @elseif($header->status == 1012)
                                            <span class="badge status-draft m-0" style="width: 100%"> DRAFT </span>
                                             @elseif($header->status == 1013)
                                            <span class="badge status-disapproved m-0" style="width: 100%"> CANCEL </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">{{ date("M d, Y", strtotime($header->created_at)) }}</td>
                                    <td style="text-align: center" id="actions">
                                        <a class="btn btn-secondary"  data-bs-toggle="modal" title="View" data-bs-target="#requestDetailModal" data-request-id="{{ $header->id }}" style="vertical-align: center; text-align: center"><i class="fa-solid fa-eye"></i></a>
                                        <a class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#requestDetailModal"  data-print="true" data-bs-toggle="tooltip" title="Print" data-request-id="{{ $header->id }}" style="vertical-align: center; text-align: center"><i class="fa-solid fa-print"></i>
                                       
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for viewing request details -->
    @include('admin.components.modal')
    

    <script>
        $(document).ready(function (){
            // dashboardTable = new DataTable('#dashboard-table', {
            //     columns: [null, null, null, null, {width: '20%'}, null, null, null],
            //     language: {
            //         searchPlaceholder: 'Search records'
            //     },
            //     // autoWidth: false,
            //     // columnDefs: [
            //     //     { "width": "20px", "targets": 4 } // Adjust target index as needed
            //     // ]
            // });
            // dashboardTable.columns.adjust().draw();

            var dashboardTable = $('#dashboard-table').DataTable({
                "autoWidth": false, // Disable automatic column width calculation
                "columnDefs": [
                    { "width": "20%", "targets": [4] } // Set width for the 5th column (index 4)
                ],
                "language": {
                    "searchPlaceholder": "Search records"
                }
            });

            // Adjust column widths after initialization
            dashboardTable.columns.adjust().draw();

            // To check if daterangepicker is changed
            let dateRangeIsChanged = false;

            $('input[name="date-range"]').daterangepicker({
                autoUpdateInput: false,
                timePicker: true,
                timePicker24Hour: false, // Use 24-hour format
                locale: {
                    format: 'MMMM D, YYYY hh:mm A'
                },
                // startDate: ''
            });

            $('input[name="date-range"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MMMM D, YYYY hh:mm A') + ' - ' + picker.endDate.format('MMMM D, YYYY hh:mm A'));
            
                dateRangeIsChanged = true;
            });

            $('input[name="date-range"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            processFilters();

            // For Filtering
            const filterBtn = $('#filter-btn');

            filterBtn.on('click', function () {
                let dashboardUrl = "{{ route('filter') }}";

                let department = $('#filter-department').val();

                let requestedVehicle = $('#filter-requested-vehicle').val();
                let checkedStatuses = document.querySelectorAll('[name="statuses[]"]:checked');
                let statuses = Array.from(checkedStatuses).map(checkbox => checkbox.value);

                let dateInput = $('#date-range');
                let dateRange = dateInput.data('daterangepicker');
                let dateTimeStart = dateRange.startDate.format('YYYY-MM-DD_HH:mm:ss');
                let dateTimeEnd = dateRange.endDate.format('YYYY-MM-DD_HH:mm:ss');
                let dateValue = $('input[name="date-range"]').val();

                let filterQuery = '?';
                let count = 0;

                if (department != '' || "{{session('user_role') !== 'dept_secretary'}}") {
                    filterQuery = filterQuery + (count != 0 ? '&' : '') + 'dept=' + department;
                    count++;
                }

                if (requestedVehicle != '') {
                    filterQuery = filterQuery + (count != 0 ? '&' : '') + 'vehicle=' + requestedVehicle;
                    count++;
                }

                if (statuses.length != 0) {
                    filterQuery = filterQuery + (count != 0 ? '&' : '') + 'statuses=' + statuses;
                    count++;
                }

                // console.log(dateValue);
                // console.log(dateTimeStart + "|" + dateTimeEnd);
                // alert(dateValue);
                if (dateValue.length != 0) { //dateRangeIsChanged) {
                    filterQuery = filterQuery + (count != 0 ? '&' : '') + "dateRange=" + dateTimeStart + "|" + dateTimeEnd;
                    count++;
                }
    
                if (count > 0) {
                    window.location.href = dashboardUrl + filterQuery;
                }
            });

            $('.counter-card').on('click', function () {  
                let dashboardUrl = "{{ route('filter') }}";
                let filterQuery = '?';

                status = $(this).data('status');
                
                filterQuery = filterQuery + 'statuses=' + status;
                window.location.href = dashboardUrl + filterQuery;
            });

            // To reset datatable
            const resetBtn = $('#reset-btn');

            resetBtn.on('click', function () {
                window.location.href = "{{ route('dashboard')}}";
            });

            $(window).trigger('resize');
        });

        // If there are filters, change the values of the filters(buttons, pickers, checkboxes, etc.)
        function processFilters() {
            const url = location.href;

            const filterStartIndex = url.indexOf('?');
            if (filterStartIndex === -1) {
                return;
            }

            const filterString = url.slice(filterStartIndex + 1); // Add 1 to exclude '?'

            const filters = filterString.split('&');

            const filtersMap = new Map();

            filters.forEach((value) => {
                let unfiltered = value.split('=');
            
                if (unfiltered.length === 2) {
                    filtersMap.set(unfiltered[0], unfiltered[1]);
                }
            });

            filtersMap.forEach((value, key) => {
                // alert(key + " | igit | " + value);
                populateFilters(key, value);
            });
            // alert(filters[1]);
        }

        function populateFilters(filter, value) {

            switch (filter) {
                case 'dept':
                    decodedDept = (decodeURIComponent(value));
                    $('#filter-department').val(decodedDept);
                    break;
                    
                case 'vehicle':
                    decodedVehicle = (decodeURIComponent(value));
                    $('#filter-requested-vehicle').val(decodedVehicle);
                    break;
                    
                case 'statuses':
                    let checkedStatuses = value.split(',');
                    for(index = 0; index < checkedStatuses.length; index++) {
                        switch (checkedStatuses[index]) {
                            case '1':
                                $('#checkbox-saved').prop('checked', true);
                                break;
                                
                            case '2':
                                $('#checkbox-posted').prop('checked', true);
                                break;
                            
                            case '3':
                                $('#checkbox-approved').prop('checked', true);
                                break;
                            
                            case '4':
                                $('#checkbox-completed').prop('checked', true);
                                break;

                            case '5':
                                $('#checkbox-hold').prop('checked', true);
                                break;

                            case '6':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;

                            case '1006':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;

                            case '1007':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;

                            case '1008':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;

                            case '1009':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;

                            case '1010':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;
                            
                            case '1011':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;

                            case '1012':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;

                            case '1013':
                                $('#checkbox-disapproved').prop('checked', true);
                                break;
                            default:
                                break;
                        }
                    }
                    break;
                    
                case 'dateRange':
                    const dateRangePicker = $('#date-range').data('daterangepicker'); // $('input[name="date-range"]').data('daterangepicker');

                    const dates = value.split('|');

                    const toProcessStart = dates[0].replace('_',' ');
                    const toProcessEnd = dates[1].replace('_',' ');

                    let startDate = moment(toProcessStart, 'YYYY-MM-DD hh:mm A');
                    let endDate = moment(toProcessEnd, 'YYYY-MM-DD hh:mm A');
                    
                    dateRangePicker.setStartDate(startDate);
                    dateRangePicker.setEndDate(endDate);

                    $('input[name="date-range"]').val(startDate.format('MMMM D, YYYY hh:mm A') + ' - ' + endDate.format('MMMM D, YYYY hh:mm A'));

                    $('input[name="date-range"]').trigger('change');
                    break;

                default:
                    break;
            }
        }
    </script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('.dropdown-menu-checkboxes input[type="checkbox"]');
    const displayText = document.getElementById('status-selected-text');

    function updateSelectedStatuses() {
        const selectedLabels = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.nextElementSibling.innerText.trim());

        displayText.textContent = selectedLabels.length > 0 
            ? selectedLabels.join(', ') 
            : 'Status Options';
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedStatuses);
    });

    updateSelectedStatuses();
});
</script>

<script>
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
</script>


@endsection
