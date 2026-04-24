@extends('layouts.app')

@section('styles')

@endsection

@section('content')
    <div class="card">
    <div class="card-header">
        <h5>User List</h5>
    </div>
    <div class="card-body">
        <table id="usersTable" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th style="width: 180px;">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

    

<script>
$(document).ready(function () {
    let table = $('#usersTable').DataTable({
        ajax: "{{ route('user.list') }}",
        processing: true,
        columns: [
            { data: 'id' },
            { data: 'full_name' },
            { data: 'department' },
            { data: 'username' },
            { data: 'email' },
            { data: 'status' },
            { data: 'action' }
        ],
        columnDefs: [
            { targets: [5, 6], orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '.toggle-active', function () {
        let userId = $(this).data('id');

        $.ajax({
            url: '/users/' + userId + '/toggle-active',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                table.ajax.reload(null, false);
            },
            error: function () {
                alert('Something went wrong.');
            }
        });
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
