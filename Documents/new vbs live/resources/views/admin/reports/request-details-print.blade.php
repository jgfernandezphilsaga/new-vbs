<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- FontAwesome and Bootstrap -->
    <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.css" rel="stylesheet"> -->
    <link href="{{ asset('assets/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fontawesome/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fontawesome/css/solid.css') }}" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Navbar -->
    <link href="{{ asset('css/dashboard/nav.css') }}" rel="stylesheet">
    
    <!-- Dashboard -->
    <link href="{{ asset('css/dashboard/cards.css') }}" rel="stylesheet"> <!-- Header Cards CSS -->
    <link href="{{ asset('css/dashboard/table.css') }}" rel="stylesheet"> <!-- Table CSS -->
    <link href="{{ asset('css/dashboard/status.css') }}" rel="stylesheet"> <!-- Button CSS -->
    
    <!-- Modal -->
    <link href="{{ asset('css/dashboard/buttons.css') }}" rel="stylesheet"> <!-- Button CSS -->

    <!-- SEO CSS -->    
    <link href="{{ asset('css/seo/seo.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Canvas Assets -->
    <link href="{{ asset('canvas/seo/seo.css') }}" rel="stylesheet">

    <!-- DataTables -->
    <!-- <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.css" rel="stylesheet"> -->
    <link href="{{ asset('assets/datatables-test/datatables.min.css') }}" rel="stylesheet">

    <!-- DateRange Picker -->
    <link href="{{ asset('assets/daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Styles -->
    <style>
        body { 
            font-family: 'Poppins', sans-serif !important; 
            font-size: 12px;
            text-size-adjust: auto;
            margin: 3.302mm 6.3mm; 
        }

        p {
            margin: 0px;
        }

        h1,h2,h3,h4,h5 {
            margin: 0px;
            padding: 0px;
        }

        .antialiased {
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        .navbar {
            box-shadow: 0 2px 4px 0 rgba(0,0,0, 0.2);
        }
        
        .navbar-items {
            width: 80%;
            margin: 0 auto;
            /* padding: 15px; */

            display: flex;
            justify-content: space-between;
        }

        .navbar-title {
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 1px;

            display: flex;
            align-items: center;
            padding: 10px 0px;
        }

        .navbar-items > ul {
            display: flex;
            flex-direction: row;
        }

        .navbar-items > ul > li {
            color: rgb(68, 68, 68);
            font-size: 15px;
            font-weight: 700;
            
            display: flex;
            align-items: center;
            padding: 5px 15px;
            letter-spacing: px;
        }

        .navbar-items > ul > li:hover{
            color: rgb(254, 150, 3);
        }

        .content {
            width: 80%;
            margin: 0 auto;
            padding : 20px 0px;
        }

        .card {
            background-color: rgb(248, 249, 250);

            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.15);
            text-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.15);

            border: 0px;

            padding: 0px;
        }

        .card-icon {
            position: relative; 
            transform: rotate(10deg); 
            top: -40px; 
            right: -0px;
        }

        th {
            background-color: #E3E3E3;
        }

        td {
            vertical-align:middle;
        }

        .action-btn {
            height: 18px;
            width: 18px;
            overflow: hidden;
            padding: 0px;
            border: 0px;

            background-color: #42A5F5;
            color: white;
            vertical-align:middle;
            align-items: center;
        }

        .body {
            width:fit-content;
        }
    </style>
</head>
<body>
    <div class="modal-content">
        <div id="requestDetailModal" class="modal-body" data-request-id="{{ $header->id }}">
            <div class="p-dev" id="printable-div">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex flex-row">
                            <div class="flex-grow-1 text-center">
                                <div class="d-flex flex-row align-items-center justify-content-evenly">
                                    <img class="modal-left-title-image" src="{{ asset('assets/images/logo/pmc-logo.png') }}" alt="PMC logo">
                                    <img class="modal-left-title-image" src="{{ asset('assets/images/logo/mmprc-logo.png') }}" alt="MMPRC logo">
                                </div>
                            </div>
                            <div class="flex-grow-1 flex-shrink-0">
                                <div class="d-flex flex-column align-items-center">
                                    <h4 class="view-details-headers">Philsaga Mining Corporation</h4>
                                    <div class="mt-2">
                                        <p class="mt-1 mb-0 view-details-headers" >Material Control Department</p>
                                        <p class="m-0 view-details-headers" >MCD-Form-0 Rev.1</p>
                                        <p class="m-0 view-details-headers" >Effectivity Date: Sept. 01, 2023</p>
                                    </div>
                                    <h5 class="my-3 view-details-headers" style="font-weight:bold;">VEHICLE REQUISITION SLIP</h5>
                                </div>
                            </div>
                            <div class="flex-grow-1 text-center">
                                <div class="d-flex flex-row align-items-center justify-content-evenly">
                                    <img class="modal-right-title-image" src="{{ asset('assets/images/logo/pmc-values.png') }}" alt="PMC values">
                                    <img class="modal-right-title-image" src="{{ asset('assets/images/logo/iso-cert.png') }}" alt="PMC ISO cert">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-between mb-1">
                    <div class="col-md-5 d-flex flex-column justify-content-center">
                        <div class="d-flex flex-row">
                            <p class=" m-0" >Requesting Dept.: </p>
                            <div class="placeholder-glow mx-2 flex-grow-1">
                                <span class="w-100" id="requesting-dept"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex flex-column" style="text-align:end">
                        <div class="d-flex flex-row" style="text-align:end">
                            <p class="m-0 flex-grow-1">Date: </p>
                            <div class="placeholder-glow mx-2">
                                <span class="" id="header-date" style="width: 15em"></span>
                            </div>
                        </div>
                        <div class="d-flex flex-row">
                            <p class="m-0 flex-grow-1" >Requested Vehicle: </p>
                            <div class="placeholder-glow mx-2">
                                <span class="" id="requested-vehicle" style="width: 15em"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered mb-1">
                            <thead>
                                <th style="vertical-align:middle; text-align:center; width: 16%">Date</th>
                                <th style="vertical-align:middle; text-align:center; width: 10%">Time of Departure</th>
                                <th style="vertical-align:middle; text-align:center; width: 10%">Requested Hour(s)</th>
                                <th style="text-align:center; width: 44%; padding: 0px" colspan="2">
                                    <div class="d-flex flex-column">
                                        <p style="vertical-align:top; border-bottom:solid; border-bottom-width: 1px; border-color: rgb(222, 226, 230)">Destination</p>
                                        <div class="d-flex flex-row">
                                            <p class="col-6">From</p>
                                            <p class="col-6" style="border-left:solid; border-left-width: 0px; border-color: rgb(222, 226, 230)">To</p>
                                        </div>
                                    </div>
                                </th>
                                <th style="text-align:center; width: 20%">Name of Passenger(s)</th>
                            </thead>
                            <tbody id="modal-tbody">
                                <!-- @for($i = 0; $i < 6; $i++)
                                    <tr id="row-{{ $i }}">        
                                        <td class="p-1">
                                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                                <span class="placeholder w-100" id="date-{{ $i }}"></span>
                                            </div>
                                        </td>
                                        <td class="p-1">
                                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                                <span class="placeholder w-100" id="departure-{{ $i }}"></span>
                                            </div>
                                        </td> 
                                        <td class="p-1">
                                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                                <span class="placeholder w-100" id="hours-{{ $i }}"></span>
                                            </div>
                                        </td>  
                                        <td class="p-1">
                                            <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                                <span class="placeholder w-100" id="destination-from-{{ $i }}"></span>
                                            </div>
                                        </td>    
                                        <td class="p-1">
                                            <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                                <span class="placeholder w-100" id="destination-to-{{ $i }}"></span>
                                            </div>
                                        </td> 
                                        <td class="p-1">
                                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                                <span class="placeholder w-100" id="passengers-{{ $i }}"></span>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor -->
                            </tbody>
                        </table>
                        <p style="font-size: 0.7rem;">Note: Submit the requisition (2) two days before the scheduled day trup and must notify (1) one day before to requesting department if approve or reject.</p>
                        <div class="d-flex flex-row my-3">
                            <p class="mb-0">Purpose: </p>
                            <div class="placeholder-glow mx-2 flex-grow-1" id="purpose-content">
                                <span class="w-100" id="purpose"></span>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex flex-row justify-content-evenly">
                            <div class="col-md-3 d-flex flex-column ">
                                <p class="mb-0">Requested By:</p>
                                <div class="placeholder-glow mx-2 flex-grow-1 w-100" style="text-align:center">
                                    <span class="w-100" id="requested-by"></span>
                                </div>
                                <hr class="m-0 w-100">
                                <p style="text-align: center">Name/Signature</p>
                            </div>
                            <div class="col-md-3 d-flex flex-column ">
                                <p class="mb-0">Approved By:</p>
                                <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                    <span class="w-100" id="approved-by"></span>
                                </div>
                                <hr class="m-0">
                                <p style="text-align: center">Requesting Dept. Manager</p>
                            </div>
                            <div class="col-md-3 d-flex flex-column ">
                                <p class="mb-0">Acknowledged By:</p>
                                <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                    <span class="w-100" id="acknowledged-by"></span>
                                </div>
                                <hr class="m-0">
                                <p style="text-align: center">Dispatcher/Dept. Manager</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if(null !== session('role'))
                @if(session('role') == 'dispatcher')
                    <b style="font-size: 0.75vw">Post this request?</b>
                    <button type="button" class="btn btn-post" id="post-btn">Post</button>
                @elseif(session('role') == 'manager')
                    <b style="font-size: 0.75vw">Approve this request?</b>
                    <button type="button" class="btn btn-approve" id="approve-btn">Approve</button>
                @endif
            @endif
            <!-- <button type="button" class="btn btn-info" style="color:white" data-bs-dismiss="modal"><i class="fa-solid fa-print"></i> Print</button>
            <a href="#"><button class="btn btn-danger" type="button" id="reject-btn">Reject</button></a> -->
        </div>
    </div>
    <!-- jQuery Scripts -->
    <script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // $("#header-date").text(formatDate(header_values['created_at']));
            resetModal();

            var id = $('#requestDetailModal').attr('data-request-id');
            
            $.ajaxSetup({ 
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                } 
            });
            
            $.ajax({
                url: "{{ url('request/show') }}/" + id,
                method: 'GET',
                success: function(response) {
                    // 
                    let row_count = 6;
                    let header_values = response.view[0];

                    var status = header_values['status'];
                    var $statusSpan = $('#status-span');
                    var statusClass;

                    switch (status) {
                        case 'DRAFT':
                            statusClass = 'status-draft';
                            break;
                        case 'POSTED':
                            statusClass = 'status-posted';
                            break;
                        case 'APPROVED':
                            statusClass = 'status-approved';
                            break;
                        case 'COMPLETED':
                            statusClass = 'status-completed';
                            break;
                        default:
                            statusClass = 'bg-secondary';
                    }
                    $statusSpan.addClass(statusClass);
                    $statusSpan.text(status);
                    
                    $("#requesting-dept").removeClass("placeholder");
                    $("#header-date").removeClass("placeholder");
                    $("#requested-vehicle").removeClass("placeholder");
                    $("#purpose").removeClass("placeholder");
                    $("#requested-by").removeClass("placeholder");
                    $("#approved-by").removeClass("placeholder");
                    $("#acknowledged-by").removeClass("placeholder");

                    $("#requesting-dept").text(header_values['department']);
                    $("#header-date").text(formatDate(header_values['created_at']));
                    $("#requested-vehicle").text(header_values['vehicle']);
                    $("#purpose").text(header_values['purpose']);
                    $("#requested-by").text(header_values['user']);
                    // $("#requested-by").text('asfsasdasdadfsdf');
                    $("#approved-by").text(header_values['dept_approver']);
                    $("#acknowledged-by").text(header_values['gsd_dispatcher']);

                    // Add more rows if details exceed default amount
                    if(response.view.length > 6) {
                        let excessRowsCount = response.view.length - 6; // 6 is the default number of rows
                        let rowIndex =  response.view.length - 1; // Set index for rows to be added 

                        for(excessRowsCount; excessRowsCount > 0; excessRowsCount--) {
                            var newRow = `
                                <tr id="row-${rowIndex}">        
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="placeholder w-100" id="date-${rowIndex}"></span>
                                        </div>
                                    </td>
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="placeholder w-100" id="departure-${rowIndex}"></span>
                                        </div>
                                    </td> 
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="placeholder w-100" id="hours-${rowIndex}"></span>
                                        </div>
                                    </td>  
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                            <span class="placeholder w-100" id="destination-from-${rowIndex}"></span>
                                        </div>
                                    </td>    
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                            <span class="placeholder w-100" id="destination-to-${rowIndex}"></span>
                                        </div>
                                    </td> 
                                    <td class="p-1">
                                        <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                            <span class="placeholder w-100" id="passengers-${rowIndex}"></span>
                                        </div>
                                    </td>
                                </tr>
                            `;

                            // Append row to table body
                            $('#modal-tbody').append(newRow);
                            rowIndex++;
                        } 
                    }

                    let loopCount = 0;
                    for (let index = 0; index < response.view.length; index++) {
                        let values = response.view[index];

                        $(`#date-${index}`).removeClass("placeholder");
                        $(`#departure-${index}`).removeClass("placeholder");
                        $(`#hours-${index}`).removeClass("placeholder");
                        $(`#destination-from-${index}`).removeClass("placeholder");
                        $(`#destination-to-${index}`).removeClass("placeholder");
                        $(`#passengers-${index}`).removeClass("placeholder");

                        $(`#date-${index}`).text(formatDate(values['created_at']));
                        $(`#departure-${index}`).text(formatTime(values['created_at']));
                        $(`#hours-${index}`).text(values['requested_hrs']);
                        $(`#destination-from-${index}`).text(values['destination_from']);
                        $(`#destination-to-${index}`).text(values['destination_to']);
                        $(`#passengers-${index}`).text(values['passengers']);

                        loopCount++;
                    }

                    if(loopCount < row_count) {
                        for(loopCount; loopCount < row_count; loopCount++) {
                            $(`#date-${loopCount}`).removeClass("placeholder");
                            $(`#departure-${loopCount}`).removeClass("placeholder");
                            $(`#hours-${loopCount}`).removeClass("placeholder");
                            $(`#destination-from-${loopCount}`).removeClass("placeholder");
                            $(`#destination-to-${loopCount}`).removeClass("placeholder");
                            $(`#passengers-${loopCount}`).removeClass("placeholder");

                            $(`#date-${loopCount}`).html('&nbsp;');
                            $(`#departure-${loopCount}`).html('&nbsp;'); 
                            $(`#hours-${loopCount}`).html('&nbsp;');
                            $(`#destination-from-${loopCount}`).html('&nbsp;');
                            $(`#destination-to-${loopCount}`).html('&nbsp;');
                            $(`#passengers-${loopCount}`).html('&nbsp;'); 

                        }
                    }
                    
                    window.print();
                },
                error: function(data) {
                    alert('Error: ');
                }
            });
        });

        function formatDate(isoString) {
            // Parse the ISO date string
            const date = new Date(isoString);

            // const formatter = new Intl.DateTimeFormat('en-US').format(date);
            const splitDate = isoString.split('T');
            formatter = splitDate[0];

            // Format the date
            return formatter;
        }

        function formatTime(isoString) {
            // Parse the ISO date string
                const date = new Date(isoString);

                // Create a formatter for the desired time format
                const options = { hour: 'numeric', minute: 'numeric', hour12: true };
                const formatter = new Intl.DateTimeFormat('en-US', options);

                // Format the time
                return formatter.format(date);
            }

        function resetModal() {
            const $modalTbody = $('#modal-tbody');
            $modalTbody.empty();

            $('#requestDetailModal').data('request-id', '');

            var $statusSpan = $('#status-span');

            $statusSpan.removeClass(function(index, className) {
                return (className.match(/(^|\s)bg-\S+/g) || []).join(' ');
            });

            $statusSpan.text('STATUS');

            $('#requesting-dept').addClass('placeholder');
            $('#header-date').addClass('placeholder');
            $('#requested-vehicle').addClass('placeholder');
            $('#purpose').addClass('placeholder');
            $('#requested-by').addClass('placeholder');
            $('#approved-by').addClass('placeholder');
            $('#acknowledged-by').addClass('placeholder');

            for(index = 0; index < 6; index++) {
                var row = `
                    <tr id="row-${index}">        
                        <td class="p-1">
                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                <span class="placeholder w-100" id="date-${index}"></span>
                            </div>
                        </td>
                        <td class="p-1">
                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                <span class="placeholder w-100" id="departure-${index}"></span>
                            </div>
                        </td> 
                        <td class="p-1">
                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                <span class="placeholder w-100" id="hours-${index}"></span>
                            </div>
                        </td>  
                        <td class="p-1">
                            <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                <span class="placeholder w-100" id="destination-from-${index}"></span>
                            </div>
                        </td>    
                        <td class="p-1">
                            <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0" style="text-align:center">
                                <span class="placeholder w-100" id="destination-to-${index}"></span>
                            </div>
                        </td> 
                        <td class="p-1">
                            <div class="placeholder-glow mx-2 flex-grow-1" style="text-align:center">
                                <span class="placeholder w-100" id="passengers-${index}"></span>
                            </div>
                        </td>
                    </tr>
                `;

                $('#modal-tbody').append(row);
            }
        }
    </script>
</body>
</html>