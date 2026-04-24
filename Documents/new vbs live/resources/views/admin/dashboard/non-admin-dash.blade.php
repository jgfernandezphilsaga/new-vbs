@extends('layouts.app')

@section('content')
    <div class="row cards-row pt-0">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body row">
                    <div class="card-title d-flex flex-row justify-content-between">
                        <div class="d-flex align-items-baseline mb-3">
                            <h5 style="vertical-align: middle; height:100%; position:relative; top: 5px; line-height: 1.5"  >Summary</h5>
                        </div>
                    </div>
                    <div class="row justify-content-evenly m-0">
                        <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card dash-saved-card counter-card" data-status="1">
                                <div class="card-title p-2 m-0">> SAVED</div>
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
                                <div class="card dash-posted-card counter-card" data-status="2">
                                    <div class="card-title p-2 m-0">> POSTED</div>
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
                                <div class="card-title p-2 m-0">> APPROVED</div>
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
                            <div class="card dash-completed-card counter-card" data-status="4">
                                <div class="card-title p-2 m-0">> COMPLETED</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;">{{ $completed_count }}</h3>
                                        <!-- <i class="fa-solid fa-ban fa-5x card-icon"></i> -->
                                        <i class="fa-solid fa-list-check fa-5x card-icon"></i> <!--  style="border: 4px solid; border-radius: 5px; padding: 4px;" -->
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-2 col-sm-6 card-container p-0">
                            <div class="card dash-cancelled-card">
                                <div class="card-title p-2 m-0">> CANCELLED</div>
                                <div class="card-body">
                                    <div class="p-0 m-0" style="display: flex; flex-direction:row; justify-content:space-between;">
                                        <h1 style="font-weight:bold; margin:0px; padding:0px;">{{ $cancelled_count }}</h3>
                                        <i class="fa-solid fa-circle-xmark fa-5x card-icon" ></i>
                                        <i class="fa-solid fa-ban fa-5x card-icon"></i>
                                    </div>    
                                </div>
                            </div>
                        </div> -->
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
                            <select class="form-select form-select-lg me-2" style="font-size: 12px; width: 10vw; min-width: 2vw;" id="filter-department" name="filter_department" aria-label="Large select example" style="font-size: 13px;">
                                <option value="" selected> Select department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                                
                                <!-- <option value="IT">IT</option> -->
                                <!-- <option value="DMS">DMS</option> -->
                                <!-- <option value="Admin">Admin</option> -->
                            
                            </select>
                            <select class="form-select form-select-lg me-2" style="font-size: 12px; width: 10vw; min-width: 2vw;" id="filter-requested-vehicle" name="filter_requested_vehicle" aria-label="Large select example" style="font-size: 13px;">
                                <option value="" selected> Select a vehicle</option>
                                <option value="1">Pickup</option>
                                <option value="2">Bus</option>
                                <option value="3">Truck</option>
                            </select>
                            <div class="dropdown me-2" style="font-size: 14px; height: 100%">
                                <button class="btn form-control dropdown-toggle" style="font-size: 13px; height: 100%; background-color: #fff; border-width: 1px; border-color: #dee2e6;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Status Options
                                </button>
                                <ul class="dropdown-menu dropdown-menu-checkboxes" style="padding: 5px 10px;"  aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="statuses[]" value="1" id="checkbox-saved">
                                            <label class="form-check-label filter-status-btn-label" style="font-size: 13px;" for="checkbox-saved">
                                                SAVED
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="statuses[]" value="2" id="checkbox-posted">
                                            <label class="form-check-label filter-status-btn-label" style="font-size: 13px;" for="checkbox-posted">
                                                POSTED
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="statuses[]" value="3" id="checkbox-confirmed">
                                            <label class="form-check-label filter-status-btn-label" style="font-size: 13px;" for="checkbox-confirmed">
                                                CONFIRMED
                                            </label>
                                        </div>
                                    </li>
                                    <!-- <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="statuses[]" value="4" id="checkbox-completed">
                                            <label class="form-check-label filter-status-btn-label" style="font-size: 13px;" for="checkbox-completed">
                                                COMPLETED
                                            </label>
                                        </div>
                                    </li> -->
                                </ul>
                            </div>
                            <input type="text" class="form-control me-2" style="font-size: 12px; width: 17vw; min-width: 2vw;" id="date-range" name="date-range" value="" placeholder="Input date range" autocomplete="off"/>
                            <!-- <label>Input date range</label> -->
                            <!-- <input type="datetime-local" class="form-control" style="font-size: 13px; width: 17vw" placeholder="asdf"/> -->
                            <button type="button" class="btn btn-primary ms-2" id="reset-btn"><i class="fa-solid fa-arrow-rotate-left"></i></button>
                            <button type="button" class="btn btn-success ms-2" id="filter-btn"><i class="fa-solid fa-filter"></i></button>
                            <a href="{{ route('create.request') }}" class="btn btn-primary ms-2" style="white-space:nowrap"><i class="fa-solid fa-circle-plus"></i> Add Request</a>
                        </div>    
                    </div>
                    <table id="dashboard-table" class="table table-hover table-bordered table-striped table-custom mb-0" style="border-top-left-radius: 8px">
                        <thead>
                            <tr>    
                                <th style="text-align:start; background-color: #E3E3E3">ID</th>
                                <!-- <th style="text-align:start; background-color: #E3E3E3">Requesting Dept</th> -->
                                <!-- <th style="text-align:start; background-color: #E3E3E3">Requested By</th> -->
                                <th style="text-align:start; background-color: #E3E3E3">Vehicle</th>
                                <th style="text-align:start; background-color: #E3E3E3">Purpose</th>
                                <th style="text-align:start; background-color: #E3E3E3">Status</th>
                                <th style="text-align:start; background-color: #E3E3E3">Requested On</th>
                                <th style="text-align:start; background-color: #E3E3E3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request_headers as $header)
                                <tr>
                                    <td style="text-align: center">{{ $header->id }}</td>
                                    <!-- <td style="text-align: center">{{ $header->requesting_dept }}</td> -->
                                    <!-- <td style="text-align: center">{{ $header->user_id }}</td> -->
                                    <td style="text-align: center">
                                        @if($header->requested_vehicle == 1)
                                            Pickup 
                                        @elseif($header->requested_vehicle == 2)
                                            Bus 
                                        @elseif($header->requested_vehicle == 3)
                                            Truck 
                                        @endif
                                    </td>
                                    <td style="text-align: center">{{ $header->purpose }}</td>
                                    <td style="text-align:center">
                                        @if($header->status == 1)
                                            <span class="badge status-saved m-0"> SAVED </span>
                                        @elseif($header->status == 2)
                                            <span class="badge status-posted m-0"> POSTED </span>
                                        @elseif($header->status == 3)
                                            <span class="badge status-approved m-0"> APPROVED </span>
                                        @elseif($header->status == 4)
                                            <span class="badge status-completed m-0"> COMPLETED </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">{{ $header->created_at }}</td>
                                    <td style="text-align:center" id="actions">
                                        <a class="btn btn-secondary"  data-bs-toggle="modal" data-bs-target="#requestDetailModal" data-request-id="{{ $header->id }}" style="vertical-align: center; text-align: center"><i class="fa-solid fa-eye"></i></a>
                                        @if(null !== session('user_role'))
                                            @if((session('user_role') == 'gsd_dispatcher' || session('user_role') == 'dept_secretary' ) && $header->status == '1')
                                                <a class="btn btn-warning" href="{{ route('edit.request', ['id' => $header->id]) }}"><i class="fa-solid fa-pen-to-square" style="color:white"></i></a>
                                            @endif
                                        @endif
                                        <!-- <a class="btn btn-info" href="#" onclick="printDiv()"><i class="fa-solid fa-print" style="color:white"></i></a> -->
                                        <!-- <a class="btn btn-post" href="#"><i class="fa-solid fa-thumbtack" style="color:white"></i></a> -->
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

        dashboardTable = new DataTable('#dashboard-table');
        
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
        
            let filterQuery = '?';
            let count = 0;

            // let filterParams = new URLSearchParams(filterUrl.search);

            if (department != '') {
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

            if (dateRangeIsChanged) {
                filterQuery = filterQuery + (count != 0 ? '&' : '') + "dateRange=" + dateTimeStart + "|" + dateTimeEnd;
                count++;
            }
  
            if (count > 0) {
                window.location.href = dashboardUrl + filterQuery;
            }
        });

        $('.counter-card').on('click', function () {  
            let dashboardUrl = "{{ route('sec-filter') }}";
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
            populateFilters(key, value);
        });
    }

    function populateFilters(filter, value) {

        switch (filter) {
            case 'dept':
                $('#filter-department').val(value);
                break;
                
            case 'vehicle':
                $('#filter-requested-vehicle').val(value);
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
                            $('#checkbox-confirmed').prop('checked', true);
                            break;
                        
                        case '4':
                            $('#checkbox-completed').prop('checked', true);
                            break;

                        default:
                            break;
                    }
                }
                break;
                
            case 'dateRange':
                const dateRangePicker = $('#date-range').data('daterangepicker'); // $('input[name="date-range"]').data('daterangepicker');

                const dates = value.split('|');
                // console.log(dates);
                // console.log(str_replace('_',' ',dates[0]));
                const toProcessStart = dates[0].replace('_',' ');
                const toProcessEnd = dates[1].replace('_',' ');

                let startDate = moment(toProcessStart, 'YYYY-MM-DD hh:mm A');
                let endDate = moment(toProcessStart, 'YYYY-MM-DD hh:mm A');
                
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
@endsection
