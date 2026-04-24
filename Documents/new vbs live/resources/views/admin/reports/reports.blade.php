@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column">
        
        <div class="d-flex flex-column card">
            <div class="card-header">
                <div class="">
                    <div class="mb-3 mt-1" style="display:flex; flex-direction:row; align-items:flex-start;">
                        <div class="d-flex flex-row">
                            <h5 style="font-weight: bold">Request Reports</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <label for="filter-department">Select department</label>
                        <select class="form-select form-select-lg" style="font-size: 12px; width: 100%; min-width: 2vw;" id="filter-department" name="filter_department" aria-label="Large select example" style="font-size: 13px;">
                            <option value="" selected> Select department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="filter_requested_vehicle">Select a vehicle</label>
                        <select class="form-select form-select-lg" style="font-size: 12px; width: 100%; min-width: 2vw;" id="filter-requested-vehicle" name="filter_requested_vehicle" aria-label="Large select example" style="font-size: 13px;">
                            <option value="" selected> Select a vehicle</option>
                            @foreach($vehicle_types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="status-dropdown">Status Options</label>
                        <div class="dropdown" name="status-dropdown" style="font-size: 14px;">
                            <button class="btn form-control dropdown-toggle" style="text-align: start; font-size: 13px; height: 100%; background-color: #fff; border-width: 1px; border-color: #dee2e6;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Status Options
                            </button>
                            <ul class="dropdown-menu dropdown-menu-checkboxes" style="padding: 5px 10px;"  aria-labelledby="dropdownMenuButton">
                                @foreach($statuses as $status)
                                    @php( $status_name = strtolower($status->status) )
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="statuses[]" value="{{ $status->id }}" id="checkbox-{{ $status_name }}">
                                            <label class="form-check-label filter-status-btn-label" style="font-size: 13px;" for="checkbox-drafted">
                                                {{ $status->status }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                                
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="date-range">Select date range<span style="color:red"> *</span></label>
                        <input type="text" class="form-control" style="font-size: 12px; width: 100%; min-width: 2vw;" id="date-range" name="date-range" value="" placeholder="Input date range" autocomplete="off" required/>
                    </div>
                    <div class="col-2 d-flex flex-row justify-content-end align-items-end">
                        <button type="button" class="btn btn-success me-2" id="filter-btn"><i class="fa-solid fa-filter"></i> Filter</button>
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary" id="reset-btn"><i class="fa-solid fa-arrow-rotate-left"></i> Reset</a>
                    </div>
                </div>
            </div>
            <div class="card-body"> 
                <div class="row">
                    <table id="reports-table" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="text-align: center; background-color: #E3E3E3; font-weight: 500">ID</th>
                                <th style="text-align: center; background-color: #E3E3E3; font-weight: 500">Requesting Dept</th>
                                <th style="text-align: center; background-color: #E3E3E3; font-weight: 500">Requested By</th>
                                <th style="text-align: center; background-color: #E3E3E3; font-weight: 500">Vehicle</th>
                                <th style="text-align: center; background-color: #E3E3E3; font-weight: 500">Purpose</th>
                                <th style="text-align: center; background-color: #E3E3E3; font-weight: 500">Status</th>
                                <th style="text-align: center; background-color: #E3E3E3; font-weight: 500">Requested On</th>
                                <!-- <th style="text-align: start; background-color: #E3E3E3">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request_headers as $header)
                                <tr>
                                    <td style="text-align: center">{{ $header->reference_id }}</td>
                                    <td style="text-align: center">{{ $header->requesting_dept }}</td>
                                    <td style="text-align: center">{{ $header->full_name }}</td>
                                    <!-- <td style="text-align: center">{{ $header->user_id }}</td> -->
                                    <td style="text-align: center">{{ $header->requested_vehicle }}</td>
                                    <td style="text-align: center">
                                        <p style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $header->purpose }}
                                        </p>
                                    </td>
                                    <td style="text-align: center">
                                        @if($header->status == 1)
                                            <span class="m-0"> DRAFTED </span>
                                        @elseif($header->status == 2)
                                            <span class="m-0"> SAVED </span>
                                        @elseif($header->status == 3)
                                            <span class="m-0"> POSTED </span>
                                        @elseif($header->status == 4)
                                            <span class="m-0"> APPROVED </span>
                                        @elseif($header->status == 5)
                                            <span class="m-0"> COMPLETED </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">{{ date("M d, Y h:i A", strtotime($header->created_at)) }}</td>
                                    <!-- <td class="d-flex flex-row justify-content-center">
                                        <a class="btn btn-secondary me-2"  data-bs-toggle="modal" data-bs-target="#requestDetailModal" data-request-id="{{ $header->id }}" style="vertical-align: center; text-align: center"><i class="fa-solid fa-eye"></i></a>    
                                        <a class="btn btn-secondary me-2" ><i class="fa-solid fa-eye"></i></a>  
                                        <a class="btn btn-info me-2" href="{{ route('reports.print.request', ['id' => $header->id]) }}"><i class="fa-solid fa-print" style="color:white"></i></a>      
                                    </td> -->
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
        $(document).ready(function () {
            // $table = new DataTable('#reports-table', {
            table = $('#reports-table').DataTable({
                columns: [null, null, null, null, {width: '20%'}, null, null],
                responsive: true,
                layout: {
                    topStart: {
                        buttons: [
                            { extend: 'print', className: 'btn dark btn-outline' },
                            { extend: 'copy', className: 'btn red btn-outline' },
                            { extend: 'pdf', className: 'btn green btn-outline' },
                            { extend: 'excel', className: 'btn yellow btn-outline ' },
                            { extend: 'csv', className: 'btn purple btn-outline ' },
                            { extend: 'colvis', className: 'btn dark btn-outline', text: 'Columns'},
                        ] 
                    },
                    bottom: 'pageLength',
                },
                
                lengthMenu: [
                    [100, 200, -1],
                    [100, 200, "All"]
                ],
                
                // initial value
                pageLength: 100,
            });
        
            // To check if daterangepicker is changed
            let dateRangeIsChanged = false;

            var start = moment().subtract(1, 'months');
            var end = moment();
            
            $('input[name="date-range"]').daterangepicker({
                autoUpdateInput: true,
                timePicker: true,
                timePicker24Hour: false, // Use 24-hour format
                locale: {
                    format: 'MMMM D, YYYY hh:mm A'
                },
                startDate: start,
                endDate: end
            }, function(start, end) {
                return $('input[name="date-range"]').val(start.format('MMMM D, YYYY hh:mm A') + ' - ' + end.format('MMMM D, YYYY hh:mm A'));
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
                let reportsUrl = "{{ route('reports.index') }}";

                let department = $('#filter-department').val();

                let requestedVehicle = $('#filter-requested-vehicle').val();

                let checkedStatuses = document.querySelectorAll('[name="statuses[]"]:checked');
                let statuses = Array.from(checkedStatuses).map(checkbox => checkbox.value);

                let dateInput = $('#date-range');
                let dateRange = dateInput.data('daterangepicker');
                let dateTimeStart = dateRange.startDate.format('YYYY-MM-DD_HH:mm:ss');
                let dateTimeEnd = dateRange.endDate.format('YYYY-MM-DD_HH:mm:ss');
                let dateValue = $('#date-range').val();

                // alert(dateInput.checkValidity());
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

                if (dateValue.length != 0) { //dateRangeIsChanged) {
                    filterQuery = filterQuery + (count != 0 ? '&' : '') + "dateRange=" + dateTimeStart + "|" + dateTimeEnd;
                    count++;
                } else {
                    dateInput.reportValidity();
                }
    
                if (count > 0) {
                    window.location.href = reportsUrl + filterQuery;
                }

                // alert(location.href);
            });
            
            // To reset reports page
            const resetBtn = $('#reset-btn');

            resetBtn.on('click', function () {
                window.location.href = "{{ route('reports.index')}}";
            });
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
                    $('#filter-department').val(value);
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
                                $('#checkbox-drafted').prop('checked', true);
                                break;
                                
                            case '2':
                                $('#checkbox-saved').prop('checked', true);
                                break;
                            
                            case '3':
                                $('#checkbox-posted').prop('checked', true);
                                break;
                            
                            case '4':
                                $('#checkbox-approved').prop('checked', true);
                                break;
                                
                            case '5':
                                $('#checkbox-completed').prop('checked', true);
                                break;

                            default:
                                break;
                        }
                    }
                    break;
                    
                case 'dateRange':
                    const dateRangePicker = $('input[name="date-range"]').data('daterangepicker');

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
        };
    </script>
@endsection