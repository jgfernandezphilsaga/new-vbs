@if($status == 1012)
    <span class="badge status-draft m-0">DRAFT</span>
@elseif($status == 3)
    <span class="badge status-approved m-0">APPROVED</span>
@elseif($status == 4)
    <span class="badge status-completed m-0">COMPLETED</span>
@elseif($status == 5)
    <span class="badge status-hold m-0">HOLD</span>
@elseif($status == 6)
    <span class="badge status-disapproved m-0">DISAPPROVED</span>
@elseif($status == 1008)
    <span class="badge status-fully-approved m-0">FULLY APPROVED</span>
@elseif($status == 1006)
    <span class="badge status-in-progress m-0">IN-PROGRESS</span>
@elseif($status == 1007)
    <span class="badge status-pending m-0">PENDING</span>
@elseif($status == 1009)
    <span class="badge status-approved-open m-0">APRVD(OPEN)</span>
@elseif($status == 1010)
    <span class="badge status-approved-closed m-0">APRVD(CLOSED)</span>
@elseif($status == 1011)
    <span class="badge status-pending m-0">PENDING</span>
@elseif($status == 1013)
    <span class="badge status-disapproved m-0">CANCEL</span>
@endif