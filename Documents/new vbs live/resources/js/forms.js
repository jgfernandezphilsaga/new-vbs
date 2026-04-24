$(document).ready(function() {
    setSelect2Inputs();
});

let count = 3; // For rowId
let passengerIndex = count + 2; // For passengers index
document.addEventListener('DOMContentLoaded', (event) => {
    const reqForm = document.getElementById('request-form');
    const addBtn = document.getElementById('add-btn');
    const submitBtn = document.getElementById('submit-btn');
    const tableBody = $('#table-body');

    addBtn.addEventListener('click', function(event) {
        var newRow = document.createElement('tr');

        var rowID = getNewRowID(count);
        newRow.classList.add('data-row');
        newRow.setAttribute('data-row-id', rowID);

        newRow.innerHTML = `
            <td>
                <input type="datetime-local" class="form-control" name="datetime[]" style="width:100%; font-size:13px;" required/>
            </td>
            <td>
                <input type="number" class="form-control" name="requested_hrs[]" style="width:100%; font-size:13px;" required/>
            </td>
            <td>
                <input type="text" class="form-control" name="destination_from[]" style="width:100%; font-size:13px;" required/>
            </td>
            <td>
                <input type="text" class="form-control" name="destination_to[]" style="width:100%; font-size:13px;" required/>
            </td>
            <td>
                <select class="form-select" name="trip_type[]" style="font-size: 13px;">
                    <option value="ONE WAY">One Way</option>
                    <option value="ROUND TRIP">Round Trip</option>
                </select>    
            </td>
            <td>
                <select class="form-control emp-select2" id="passengers-`+ rowID +`" name="passengers[`+ rowID +`][]" multiple="multiple" style="width: 100%"></select>
            </td>
            <td>
                <button class="btn btn-danger" type="button" onclick="removeRow(`+ rowID +`)"><i class="fa-solid fa-circle-minus" style="color:white"></i></button>
            </td>
        `;

        $(newRow).insertBefore($('#add-btn-row'));
        count++;

        if (count > 4) {
            $('#add-btn-row').hide();
        }

        // Re-set newly added select2 input
        setSelect2Inputs(count);
    }); 

    // Submit the form using AJAX
    submitBtn.addEventListener('click', function (event) {
        alert('clicked');
        $('#submit-btn').prop('disabled', true);
        $('#submit-btn-text').text('Submitting...');

        // Check rows if all inputs are populated
        tableBody.children()
            .not('#add-btn-row')
            .each(function(index, element) {
                if(index === 0) { return; }
                
                var isRowChanged = $(this).children()
                                        .not('#trip-type-col')
                                        .not('#remove-btn-col')
                                        .each(function(index, element) {
                                            alert('row: ' + index + ' element: ' + element + ' value:' + $(this).find('input, select').first().val());

                                        });
            });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            }
        });

        $.ajax({
            url: '/store',
            method: 'POST',
            success: function(response) {
                $('#submit-btn').prop('disabled', false);
                $('#submit-btn-text').text('Submit');
            },
            error: function(data){

            }
        });
    });

});

function setSelect2Inputs () {
    const protocol = window.location.protocol;
    const hostname = window.location.hostname;
    const port = window.location.port ? `:${window.location.port}` : '';

    $('.emp-select2').select2({
        ajax:{
            url: `${protocol}//${hostname}${port}/api/hris-api.php`,
            delay: 2000,
            data: function (params) {
                var query = {
                    emp: params.term
                }

                return query;
            },
            processResults: function (data) {
                data = JSON.parse(data);
                let processedArray = [];

                for(index = 0; index < data.length; index++) {
                    let currentIndex = data[index];
                    let passengerData = `${currentIndex.EmpID}|${currentIndex.FullName}`;

                    processedArray.push({id: passengerData, text:currentIndex.FullName});
                }

                return {
                    results: processedArray 
                }
            }
        }
    });
} 

function getNewRowID(count) {
    const values = $('[data-row-id]').map(function (){
        return Number($(this).data('row-id'));
    }).get();

    values.sort((a, b) => a - b);
    
    for (let num = values[0]; num < values.length; num++) {
        if (num != values[num]) {
            return num;
        }
    }

    return count;
}

function removeRow(rowId) {
    var row = document.querySelector('[data-row-id="'+ rowId +'"]');
    row.remove();
    count--;

    if (rowId < 5) {
        $('#add-btn-row').show();
    }
}

function returnToDash() {
    const protocol = window.location.protocol;
    const hostname = window.location.hostname;
    const port = window.location.port ? `:${window.location.port}` : '';
    window.location.href = `${protocol}//${hostname}${port}`;
}