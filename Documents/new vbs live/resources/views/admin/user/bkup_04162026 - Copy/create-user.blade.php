@extends('layouts.app')

@section('content')
<div class="" style="display: flex; justify-content: center; align-items: center;  min-height: 80vh;">
    <div class="card" style="width: 60vw">
        <div class="card-title">
            <div class="mb-2" style="display:flex; flex-direction:row; align-items:flex-start;">
                <div class="d-flex flex-row mt-3 mx-auto">
                    <h5 style="font-weight: bold">Create New User</h5>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column card">
            <form autocomplete="off" method="post" action="{{ route('user.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6 form-group">
                            <label for="full-name">Full Name<span style="color:red"> *</span></label>
                            <input id="full-name" class="form-control" name="full_name" type="text" required/>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="dept">Department<span style="color:red"> *</span></label>
                            <input id="dept" class="form-control" name="dept" type="text" required/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="username">Username<span style="color:red"> *</span></label>
                            <input id="username" class="form-control" name="username" type="text" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="password">Password<span style="color:red"> *</span></label>
                            <input id="password" class="form-control" name="password" type="password" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="email">Email</label>
                            <input id="email" class="form-control" name="email" type="text"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="approver">Select Approver<span style="color:red"> *</span></label>
                            <select id="approver" class="form-select" name="approver_id"></select>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button id="cancel-form" class="btn btn-danger" type="button" onclick="history.back()">Cancel</button>
                    <button id="submit-form" class="btn btn-success" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    function formatOption(item) {
        if (!item.id) {
            return item.text;
        }

        let designation = item.designation ? item.designation : '';

        return $(`
            <div>
                <div>${item.text}</div>
                <small style="color:#6c757d;">${designation}</small>
            </div>
        `);
    }

    function formatSelected(item) {
        return item.text || item.id;
    }

    function loadSelectOptions(selector, url, placeholderText) {
        let $select = $(selector);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $select.empty();
                $select.append('<option value=""></option>');

                $.each(response.items || [], function (index, item) {
                    let option = new Option(item.text, item.id, false, false);
                    $(option).attr('data-designation', item.designation ?? '');
                    $select.append(option);
                });

                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                $select.select2({
                    placeholder: placeholderText,
                    allowClear: true,
                    width: '100%',
                    templateResult: function (item) {
                        if (!item.id) return item.text;

                        let designation = $(item.element).data('designation') || '';
                        return $(`
                            <div>
                                <div>${item.text}</div>
                                <small style="color:#6c757d;">${designation}</small>
                            </div>
                        `);
                    },
                    templateSelection: function (item) {
                        return item.text || item.id;
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    }
                });
            },
            error: function (xhr) {
                console.log('AJAX error:', xhr.responseText);
            }
        });
    }

    loadSelectOptions('#approver', "{{ url('api/get_approvers') }}", 'Select Approver');

});
</script>
@endpush