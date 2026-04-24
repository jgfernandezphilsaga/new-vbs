<style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
   html, body {
    height:100%; 
    overflow: hidden;
  }
  @page {
            size: landscape
        }
  #printSection, #printSection * {
    visibility:visible;
  }
  #printSection {
    position:absolute;
    left:0;
    top:0;
  }
}
@media print {
  .modal-footer {
    display: none !important;
  }
}

</style>

<div class="modal fade" id="confirmationModal" data-bs-target="confirmationModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="ConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Confirm?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 16px">
                    Are you sure you want to post this request?</p><br><p> Any details cannot be changed once posted.
                </p>
            </div>
            <div class="modal-footer">
                <button id="modal-post-btn" class="btn btn-post" type="button" onclick="postRequest()">Post</button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-xl fade" id="requestDetailModal" data-request-id="" tabindex="-1" aria-labelledby="detailModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">   
                <div class="d-flex flex-row" style="text-align:end; align-items:center">
                    <h1 class="modal-title fs-5 flex-grow-1 w-100" id="detailModelLabel">Request Details</h1>
                    <span id="status-span" style="margin:0px 10px; padding: 10px 8px; position: relative; display: inline-block; border-radius: 5px;">STATUS</span>
                    <span id="emergency" style="margin:0 5px; padding: 4px 6px; position: relative; display: inline-block; border-radius: 3px; background-color: red; color: white; font-size: 11px;"></span>
                    <span id="confidential" style="margin:0 5px; padding: 4px 6px; position: relative; display: inline-block; border-radius: 3px; background-color: green; color: white; font-size: 11px;"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                                        <h6 class="view-details-headers">Philsaga Mining Corporation</h6>
                                       <h6 class="view-details-headers">Mindanao Mineral Processing and Refining Corporation</h6>
                                        <div class="mt-2">
                                            <p class="mt-1 mb-0 view-details-headers" >GENERAL SERVICES DEPARTMENT</p>
                                            <p class="m-0 view-details-headers" >GSD FORM V1</p>
                                            
                                        </div>
                                        <h6 class="my-3 view-details-headers" style="font-weight:bold;">VEHICLE REQUISITION SLIP</h6>
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

                    <div class="row mb-3 small" style="font-size: 0.75rem; margin-left: 1rem;"> 
                        <div class="col-md-6">
                            <div class="mb-2 d-flex">
                                <strong class="me-2" style="width: 130px;">Reference ID:</strong>
                                <span id="reference-id"></span>
                            </div>
                            <div class="mb-2 d-flex">
                                <strong class="me-2" style="width: 130px;">Reference Origin:</strong>
                                <span id="reference-origin"></span>
                            </div>
                            <div class="mb-2 d-flex">
                                <strong class="me-2" style="width: 130px;">Requesting Dept.:</strong>
                                <span id="requesting-dept"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="d-block mb-1">Driver(s):</strong>
                                <div id="driver-details" style="max-height: 5em; overflow-y: auto;"></div>
                            </div>
                        </div>

                        <div class="col-md-2 offset-md-3"> 
                            <div class="mb-2 d-flex">
                                <strong class="me-2">Date:</strong>
                                <span id="header-date"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="d-block mb-1">Requested Vehicle:</strong>
                                <div id="requested-vehicle"></div>
                            </div>
                            <div class="mb-2">
                                <strong class="d-block mb-1">Vehicle(s):</strong>
                                <div id="actual-vehicle"></div>
                            </div>
                        </div>
                    </div>

                        
                    

                    <div class="row" style="font-size: 0.75rem; margin-top: -10px;">
                        <div class="col-md-12">
                            <table class="table table-bordered mb-1" style="font-size: 0.75rem;">
                                <thead>
                                    <tr>
                                        <th style="vertical-align:middle; text-align:center; width: 10%">Date</th>
                                        <th style="vertical-align:middle; text-align:center; width: 10%">Time of Departure</th>
                                        <th style="vertical-align:middle; text-align:center; width: 10%">Requested Hour(s)</th>
                                        <th style="text-align:center; width: 40%; padding: 0;" colspan="2">
                                            <div class="d-flex flex-column">
                                                <p class="mb-0" style="border-bottom: 1px solid #dee2e6;">Destination</p>
                                                <div class="d-flex">
                                                    <p class="col-6 mb-0">From</p>
                                                    <p class="col-6 mb-0">To</p>
                                                </div>
                                            </div>
                                        </th>
                                        <th style="text-align:center; width: 10%">Trip Type</th>
                                        <th style="text-align:center; width: 20%">Name of Passenger(s)</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-tbody">
                                    <!-- Dynamic rows go here -->
                                </tbody>
                            </table>

                            <p class="mb-1" style="font-size: 0.65rem;">
                                Note: Submit the requisition (2) two days before the scheduled trip and notify (1) one day before to requesting department if approved or rejected.
                            </p>
                            <div class="d-flex flex-row mb-2">
                                 <strong class="me-2">Original Purpose:</strong>
                                <div class="placeholder-glow mx-2 flex-grow-1 purpose-content" id="purpose-content">
                                    <span class="w-100 purpose" id="purpose1"></span>
                                </div>
                                 <strong class="me-2">Dispatch Purpose:</strong>
                                <div class="placeholder-glow mx-2 flex-grow-1 purpose-content" id="purpose-content">
                                    <span class="w-100 purpose" id="purpose"></span>
                                    <span class="read-more" id="togglePurpose">Read more</span>
                                </div>
                            </div>

                            <hr class="my-1">

                            <div class="row text-center" style="font-size: 0.75rem; margin-bottom: 0.5rem;">
                            <div class="col-md-4">
                                <p class="mb-0">Requested By:</p>
                                <span class="placeholder-glow d-block" id="requested-by"></span>
                                <hr class="my-1">
                                <p class="mb-0">Name/Signature</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0">Approved By:</p>
                                <span class="placeholder-glow d-block" id="approved-by"></span>
                                <hr class="my-1">
                                <p class="mb-0">Requesting Dept. Manager</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0">Acknowledged By:</p>
                                <span class="placeholder-glow d-block" id="acknowledged-by"></span>
                                <hr class="my-1">
                                <p class="mb-0">Dept. Manager</p>
                            </div>
                        </div>
                        <div class="row text-center mt-n1" style="font-size: 0.75rem;">
                            <div class="col-md-4 offset-md-4">
                                <span class="placeholder-glow d-block" id="acknowledged-by-division"></span>
                                <hr class="my-1">
                                <p class="mb-0">Division Manager</p>
                            </div>
                        </div>

                            <div id="remarks-section">
                                <!-- Reserved for future remarks -->
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <!-- Buttons are set in the javascript -->
                 
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#requestDetailModal').on('show.bs.modal', function(event) {
            resetModal();

            var button = $(event.relatedTarget);
            var id = button.data('request-id');

            $('#requestDetailModal').data('request-id', id);

            // Initialize header for ajax
            $.ajaxSetup({ 
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                } 
            });
        
            $.ajax({
                url: "{{ url('request/show_dispatch') }}/" + id,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    let row_count = 6;
                    let header_values = response.request_details;
                    let remarks_values = response.remarks;

                    var isConfidential = header_values?.is_confidential ?? false;
                    var isEmergency = header_values?.is_emergency ?? false;

                    var $confidential = $('#confidential');
                    var $emergency = $('#emergency');


                    if (isConfidential == 1) {
                        $confidential.text('CONFIDENTIAL').show();
                    } else {
                        $confidential.hide();
                    }

                    if (isEmergency == 1) {
                        $emergency.text('EMERGENCY').show();
                    } else {
                        $emergency.hide();
                    }

                    if (response.driver_details && response.driver_details.length > 0) {
                        let names = response.driver_details.map(function(driver) {
                            return driver.first_name + " " + driver.last_name;
                        });

                        $("#driver-details").text(names.join(", "));
                    } else {
                        $("#driver-details").text("TBA");
                    }

                     if (response.vehicle_details && response.vehicle_details.length > 0) {
                        let vehicle_details = response.vehicle_details.map(function(vehicle) {
                            return vehicle.PLATE_NO + " " + vehicle.MODEL;
                        });

                        $("#actual-vehicle").text(vehicle_details.join(', '));
                    } else {
                        $("#actual-vehicle").text("TBA");
                    }

                    
                    let requestor = header_values?.requestor_name ?? 'N/A';
                    let dept_approver = header_values?.dept_approver_fullname ?? 'N/A';
                    let gsd_dispatcher = header_values?.gsd_manager_fullname ?? 'N/A';
                    let gsd_div_manager = header_values?.division_manager ?? 'N/A';
                    


                    var status = header_values['status_desc'];
                    var $statusSpan = $('#status-span');
                    var statusClass;

                    var vehicle = header_values['vehicle'];

                    if (vehicle) {
                        $("#requested-vehicle").text(vehicle);
                    } else {
                        $("#requested-vehicle").text("TBA");
                    }


                    switch (status) {
                        case 'DRAFT':
                            statusClass = 'badge status-draft';

                            if ("{{ session('user_role') === 'dept_secretary'}}") {
                                var questionText = header_values['is_resubmitted'] ? 'Save and resubmit this request for approval?' : '';
                                var buttonText = header_values['is_resubmitted'] ? 'Resubmit' : 'Post';

                                $('#modal-footer').append(
                                    `
                                        <b style="font-size: 0.75vw">${questionText}</b>
                                        <button type="button" class="btn btn-post" onclick="showConfirmationModal()">${buttonText}</button>
                                    `
                                );

                                // $('#modal-footer').append(
                                //     `
                                //         <b style="font-size: 0.75vw">${questionText}</b>
                                //         <button type="button" class="btn btn-post" onclick="postRequest()">${buttonText}</button>
                                //     `
                                // );
                            } 
  
                                $('#modal-footer').append(
                                    `
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                    `
                                );
                            break;
                        case 'POSTED':
                            statusClass = 'badge status-posted';

                            // if ("{{ session('user_role') === 'gsd_manager'}}") {
                            //     $('#modal-footer').append(
                            //         // NEED TO UPDATE (ADD RESTRICTION WHERE CAN ONLY BE UPDATED IS THE GSD MANAGER APPROVED ALREADY)
                            //         `
                            //             <div>
                            //                 <b style="font-size: 0.75vw">Complete this request?</b>
                            //                 <button type="button" class="btn btn-approve" onclick="completeRequest()">Complete</button>
                            //                 <button type="button" class="btn btn-hold" onclick="holdRequest()">Hold</button>
                            //                 <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            //             </div>
                            //         `

                            //         // <button type="button" class="btn btn-danger" onclick="holdRequest()">Hold</button>
                            //     );

                            //     break;
                            // } else {
                                $('#modal-footer').append(
                                    `
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                         <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                    `
                                );
                                
                                break;
                            // }
                        case 'APPROVED':
                            statusClass = 'badge status-approved';

                            if ("{{ session('user_role') === 'gsd_manager'}}") {
                                $('#modal-footer').append(
                                    // NEED TO UPDATE (ADD RESTRICTION WHERE CAN ONLY BE UPDATED IS THE GSD MANAGER APPROVED ALREADY)
                                    `
                                        <div>
                                            <b style="font-size: 0.75vw">Complete this request?</b>
                                            <button type="button" class="btn btn-approve" onclick="completeRequest()">Complete</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    `
                                );

                                break;
                            } else {
                                $('#modal-footer').append(
                                    `
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                         <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                    `
                                );
                                
                                break;
                            }

                            break;
                        case 'HOLD':
                            statusClass = 'badge status-hold';
                            
                            if ("{{ session('user_role') === 'dept_secretary'}}") {
                                $('#modal-footer').append(
                                    `
                                        <b style="font-size: 0.75vw">Repost this request?</b>
                                        <button type="button" class="btn btn-post" onclick="showConfirmationModal()">Post</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                         <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                    `
                                );
                                
                                break;
                            } else {
                                $('#modal-footer').append(
                                    `
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                         <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                    `
                                );

                                break;
                            }
                        case 'COMPLETED':
                            statusClass = 'badge status-completed';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );
                             break;
                         case 'DISAPPROVED':
                            statusClass = 'badge status-disapproved';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );
                             break;
                         case 'IN-PROGRESS':
                            statusClass = 'badge status-in-progress';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );
                             break;
                             case 'FOR DESIGNATION':
                            statusClass = 'badge status-partially-approved';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );

                            break;
                            case 'FULLY APPROVED':
                            statusClass = 'badge status-fully-approved';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );

                            break;
                            case 'APPROVED(OPEN)':
                            statusClass = 'status-approved-open';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );
                            break;
                            case 'APPROVED(CLOSED)':
                            statusClass = 'status-approved-closed';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );
                            break;
                            case 'PENDING':
                            statusClass ='badge status-pending';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                     <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );
                            break;
                            case 'CANCEL':
                            statusClass = 'badge status-cancel';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );

                            break;
                            
                        default:
                            statusClass = ' badge bg-secondary';

                            $('#modal-footer').append(
                                `
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-post"  onclick="printModalContent()">Print</button>
                                `
                            );
                    }
                    $statusSpan.addClass(statusClass);
                    $statusSpan.text(status);
                    
                    $("#reference-id").removeClass("placeholder");
                    $("#reference-origin").removeClass("placeholder");
                    $("#requesting-dept").removeClass("placeholder");
                    $("#header-date").removeClass("placeholder");

                    $("#purpose").removeClass("placeholder");
                    $("#requested-by").removeClass("placeholder");
                    $("#approved-by").removeClass("placeholder");
                    $("#acknowledged-by").removeClass("placeholder");

                    $("#reference-id").text(header_values['dispatch_reference']);
                    $("#reference-origin").text(header_values['request_reference']);
                    let dept_parts = header_values['requestor_dept'].split(',');
                    let dept_filtered = dept_parts.filter((part, index) => {
                        return index === 0 || part !== dept_parts[index - 1];
                    });
                    let dept_requestor = dept_filtered.join(',');
                    $("#requesting-dept").text(dept_requestor);
                    $("#header-date").text(formatDate(header_values['created_at'], 'header'));

                    $("#purpose1").text(header_values['origin_purpose']);
                    $("#purpose").text(header_values['purpose']); 
                    if(!hasExceededLineClamp($('#purpose'))) { $('#togglePurpose').hide(); }

                    let parts = requestor.split(',');
                    let filtered = parts.filter((part, index) => {
                        return index === 0 || part !== parts[index - 1];
                    });
                    let res_requestor = filtered.join(',');
                    $("#requested-by").text(res_requestor); 
                    $("#approved-by").text(dept_approver); 
                    $("#acknowledged-by").text(gsd_dispatcher);
                    $("#acknowledged-by-division").text(gsd_div_manager);

                    
                    let values = response.request_details;
                     if (values) {
                    let passengersArray = (values?.passengers ?? '').split(',').filter(Boolean);
                    let passengers = passengersArray.join(', ');

                    $("#date-0").removeClass("placeholder").text(removeTime(values.departure_time));
                    $("#departure-0").removeClass("placeholder").text(formatTime(values.departure_time));
                    $("#hours-0").removeClass("placeholder").text(values.requested_hrs ?? '');
                    $("#destination-from-0").removeClass("placeholder").text(values.destination_from ?? '');
                    $("#destination-to-0").removeClass("placeholder").text(values.destination_to ?? '');
                    $("#trip-type-0").removeClass("placeholder").text(values.trip_type ?? '');
                    $("#passengers-0").removeClass("placeholder").html(passengers || '&nbsp;');
                } else {
                    $("#date-0, #departure-0, #hours-0, #destination-from-0, #destination-to-0, #trip-type-0, #passengers-0")
                        .removeClass("placeholder")
                        .html('&nbsp;');
                }

                    if(remarks_values.length > 0) {
                        $('#remarks-section').append(`
                            <hr class="my-4" style="border: none; height: 2px; color: black; background-color: black;">
                            <div class="d-flex flex-column my-3">
                                <p class="mb-0" style="font-weight: bold">Remarks: </p>
                                <div class="d-flex flex-row overflow-x-auto remarks-scrollbar" id="remarks">
                                </div>
                            </div>
                        `);

                        let remarksLoopCount = 0;
                        for(let index = 0; index < remarks_values.length; index++) {
                            let remark_data = remarks_values[index];

                            let remark_status_class = '';
                            let remark_status = '';
                            switch(remark_data['status']) {
                                case 'APPROVED':
                                    remark_status_class = 'status-approved';
                                    remark_status = 'APPROVED';
                                    break;
                                case 'COMPLETED':
                                    remark_status_class = 'status-posted';
                                    remark_status = 'COMPLETED';
                                    break;
                                case 'HOLD':
                                    remark_status_class = 'status-hold';
                                    remark_status = 'HOLD';
                                    break;
                                case 'DISAPPROVED':
                                    remark_status_class = 'status-disapproved';
                                    remark_status = 'DISAPPROVED';
                                    break;
                                case 'IN-PROGRESS':
                                    remark_status_class = 'status-hold';
                                    remark_status = 'IN-PROGRESS';
                                    break;
                                 case 'FULLY APPROVED':
                                    remark_status_class = 'status-fully-approved';
                                    remark_status = 'FULLY APPROVED';
                                    break;
                                 case 'PARTIALLY APPROVED':
                                    remark_status_class = 'status-partially-approved';
                                    remark_status = 'PARTIALLY APPROVED';
                                    break;
                                 case 'APPROVED(OPEN)':
                                    remark_status_class = 'status-approved-open';
                                    remark_status = 'APPROVED(OPEN)';
                                    break;
                                 case 'APPROVED(CLOSED)':
                                    remark_status_class = 'status-approved-closed';
                                    remark_status = 'APPROVED(CLOSED)';
                                    break;
                                 case 'PENDING':
                                    remark_status_class = 'status-pending';
                                    remark_status = 'PENDING';
                                    break;
                                case 'CANCEL':
                                    remark_status_class = 'status-disapproved';
                                    remark_status = 'CANCEL';
                                    break;
                                default:
                                    break;
                            }

                            //  top: -13px; right: 5%
                            var newRemark = `
                                <div class="col-4 p-1">
                                    <div class="card p-2 d-flex flex-column justify-content-between" style="min-height: 76px">
                                        <div>
                                            <div class="d-flex flex-row justify-content-between">
                                                <p>${remark_data['remarks']}</p>
                                                <div class="status-span p-1 ${remark_status_class}" id="remark-status-span" style="position: relative; display: inline-block; border-radius: 5px;">
                                                    <p style="color: white">${remark_status}</p>

                                                </div>
                                            </div>
                                            <div class="d-flex flex-row mt-1 justify-content-between align-items-end" style="font-style: italic; font-size: 10px;">
                                                <p>
                                                    ${remark_data['sender_name']}<br>
                                                    ${remark_data['sender_position']}
                                                </p>
                                                <p>${formatDate(remark_data['created_at'], 'header')} ${formatTime(remark_data['created_at'])}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            $('#remarks').append(newRemark);
                        }
                    }
                },
                error: function(data) {
                    alert('Error! Login expired. Please login again');

                    location.reload();
                }
            });
        });

        $('#confirmationModal').on('hidden.bs.modal', function () {
            $('.modal-backdrop').css('z-index', 1049); 
            $('.modal-backdrop').show(); 
        });

        $('#togglePurpose').on('click', function() {
            var purposeSpan = $('#purpose');
            if (purposeSpan.hasClass('expanded')) {
                purposeSpan.removeClass('expanded');
                $(this).text('Read more');
            } else {
                purposeSpan.addClass('expanded');
                $(this).text('Read less');
            }
        });

    });

 

    function showConfirmationModal() {
        $('#requestDetailModal').css('z-index', 1050);
        $('#confirmationModal').css('z-index', 1051);

        $('#confirmationModal').modal('show');
    }


    function postRequest() {
        $('#modal-post-btn').append('<div class="spinner-border" style="height: 1rem; width: 1rem; margin-left: 5px" role="status"><span class="visually-hidden">Loading...</span></div>');
        $('#modal-post-btn').prop('disabled', true);
        var id = $('#requestDetailModal').data('request-id');
        updateRequest(id, 1011);
    }

    function completeRequest() {
        var id = $('#requestDetailModal').data('request-id');
        updateRequest(id, 4); 

    }


    function holdRequest() {
        var id = $('#requestDetailModal').data('request-id');
        updateRequest(id, 5); 

    }

    function removeTime(dateTime) {
    if (!dateTime) return '';
    let date = new Date(dateTime);
    

    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

   

    function formatDate(isoString, type) {
    if (!isoString) return ''; 

    const date = new Date(isoString);
    if (isNaN(date.getTime())) return isoString; 

    switch (type) {
        case 'header':
            return date.toISOString().split('T')[0]; 
        case 'detail': {
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            const hh = String(date.getHours()).padStart(2, '0');
            const min = String(date.getMinutes()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd} ${hh}:${min}`;
        }
        default:
            return isoString;
    }
}

    function formatTime(isoString) {

        const date = new Date(isoString);
        const options = { hour: 'numeric', minute: 'numeric', hour12: true };
        const formatter = new Intl.DateTimeFormat('en-US', options);


        return formatter.format(date);
    }

    function hasExceededLineClamp($element) {
        if (!$element.length) return false;


        var style = getComputedStyle($element[0]);
        var lineClamp = parseInt(style.getPropertyValue('-webkit-line-clamp'));

        if (isNaN(lineClamp) || lineClamp <= 0) return false;


        var lineHeight = parseInt(style.getPropertyValue('line-height'));
        if (isNaN(lineHeight) || lineHeight <= 0) return false;


        var expectedHeight = lineClamp * lineHeight;


        var actualHeight = $element[0].scrollHeight;

        return actualHeight > expectedHeight;
    }


    function truncatePurpose(source, size) {
        return source.length > size ? source.slice(0, size - 1) + "…" : source;
    }


    function resetModal() {
        const $modalTbody = $('#modal-tbody');
        $modalTbody.empty();

        $('#requestDetailModal').data('request-id', '');

        var $statusSpan = $('#status-span');

        $statusSpan.removeClass();

        $statusSpan.text('STATUS');

        $('#reference-id').addClass('placeholder');
        $('#requesting-dept').addClass('placeholder');
        $('#header-date').addClass('placeholder');
        $('#purpose').addClass('placeholder');
        $('#togglePurpose').show();
        $('#requested-by').addClass('placeholder');
        $('#approved-by').addClass('placeholder');
        $('#acknowledged-by').addClass('placeholder');

        let row = `
            <tr id="row-0">        
                <td class="p-1">
                    <div class="placeholder-glow mx-2 flex-grow-1 text-center">
                        <span class="placeholder w-100" id="date-0"></span>
                    </div>
                </td>
                <td class="p-1">
                    <div class="placeholder-glow mx-2 flex-grow-1 text-center">
                        <span class="placeholder w-100" id="departure-0"></span>
                    </div>
                </td> 
                <td class="p-1">
                    <div class="placeholder-glow mx-2 flex-grow-1 text-center">
                        <span class="placeholder w-100" id="hours-0"></span>
                    </div>
                </td>  
                <td class="p-1">
                    <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0 text-center">
                        <span class="placeholder w-100" id="destination-from-0"></span>
                    </div>
                </td>    
                <td class="p-1">
                    <div class="placeholder-glow mx-2 flex-grow-1 flex-shrink-0 text-center">
                        <span class="placeholder w-100" id="destination-to-0"></span>
                    </div>
                </td> 
                <td class="p-1">
                    <div class="placeholder-glow mx-2 flex-grow-1 text-center">
                        <span class="placeholder w-100" id="trip-type-0"></span>
                    </div>
                </td>
                <td class="p-1">
                    <div class="placeholder-glow mx-2 flex-grow-1 text-center">
                        <span class="placeholder w-100" id="passengers-0"></span>
                    </div>
                </td>
            </tr>
        `;

        $('#modal-tbody').append(row);
        
        $('#remarks-section').children().remove();

        $('#modal-footer').empty();
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('requestDetailModal');

  
  let lastTrigger = null;

  
  document.querySelectorAll('[data-bs-toggle="modal"]').forEach(el => {
    el.addEventListener('click', function () {
      lastTrigger = this;
    });
  });

  
  modal.addEventListener('shown.bs.modal', function () {
    if (lastTrigger && lastTrigger.dataset.print === "true") {
      setTimeout(() => {
        printModalContent();
      }, 300);
    }
  });
});

function printModalContent() {
  // Hide modal footer buttons before printing
  document.querySelectorAll('#modal-footer .btn').forEach(btn => {
    btn.style.display = 'none';
  });

  const modal = document.getElementById('requestDetailModal');
  const printContents = modal.innerHTML;
  const originalContents = document.body.innerHTML;

  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  location.reload();
}
</script>



    