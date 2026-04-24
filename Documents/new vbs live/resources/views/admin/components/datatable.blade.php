@props([
    'columns',
    'rows',
    'statusMap' => [],
    'tableId' => 'dynamic-table'
])

<table id="{{ $tableId }}"
       class="table table-hover table-bordered table-striped table-custom mb-0"
       style="border-top-left-radius: 8px; table-layout:fixed; width:100%">
    <thead>
        <tr>
            @foreach($columns as $col)
                <th style="text-align:center; background-color: #E3E3E3; font-weight: 500">
                    {{ $col['label'] }}
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($rows as $row)
            <tr>
                @foreach($columns as $col)
                    @php
                        $value = data_get($row, $col['field']);
                    @endphp

                    @if($col['field'] === 'status' && isset($statusMap[$value]))
                        <td style="text-align:center">
                            <span class="badge {{ $statusMap[$value]['class'] }} m-0" style="width: 100%">
                                {{ $statusMap[$value]['label'] }}
                            </span>
                        </td>
                    @else
                        <td style="text-align: center; max-width: {{ $col['maxWidth'] ?? '100%' }};
                                   overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $value }}
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
    $(function () {
        $('#{{ $tableId }}').DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            order: [[0, 'desc']],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
            ]
        }).buttons().container().appendTo('#{{ $tableId }}_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
