@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="card" style="width: 60vw">
        <div class="card-title">
            <div class="mb-2" style="display:flex; flex-direction:row; align-items:flex-start;">
                <div class="d-flex flex-row mt-3 mx-auto">
                    <h5 style="font-weight: bold">Edit User</h5>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column card">
            <form autocomplete="off" method="post" action="{{ route('user.update', $user->id) }}">
                @csrf
                @method('POST')

                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6 form-group">
                            <label for="full-name">Full Name<span style="color:red"> *</span></label>
                            <input
                                id="full-name"
                                class="form-control"
                                name="full_name"
                                type="text"
                                value="{{ old('full_name', $user->full_name) }}"
                                required
                            />
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="dept">Department<span style="color:red"> *</span></label>
                            <input
                                id="dept"
                                class="form-control"
                                name="dept"
                                type="text"
                                value="{{ old('dept', $user->department) }}"
                                required
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="username">Username<span style="color:red"> *</span></label>
                            <input
                                id="username"
                                class="form-control"
                                name="username"
                                type="text"
                                value="{{ old('username', $user->username) }}"
                                required
                            />
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="password">Password</label>
                            <input
                                id="password"
                                class="form-control"
                                name="password"
                                type="password"
                                placeholder="Leave blank if you do not want to change it"
                            />
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="email">Email</label>
                            <input
                                id="email"
                                class="form-control"
                                name="email"
                                type="text"
                                value="{{ old('email', $user->email) }}"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="approver">Select Approver<span style="color:red"> *</span></label>
                            <select id="approver" class="form-select" name="approver_id" required>
                                @if($approver)
                                    <option value="{{ $approver->id }}" selected data-designation="{{ $approver->designation }}">
                                        {{ $approver->name }}
                                    </option>
                                @else
                                    <option value=""></option>
                                @endif
                            </select>
                        </div>

                        

                        
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button id="cancel-form" class="btn btn-danger" type="button" onclick="history.back()">Cancel</button>
                    <button id="submit-form" class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    function renderOption(item) {
        if (!item.id) {
            return item.text;
        }

        let designation = '';

        if (item.element) {
            designation = $(item.element).data('designation') || '';
        } else if (item.designation) {
            designation = item.designation;
        }

        return $(`
            <div>
                <div>${item.text}</div>
                <small style="color:#6c757d;">${designation}</small>
            </div>
        `);
    }

    function loadSelectOptions(selector, url, placeholderText) {
        let $select = $(selector);
        let currentValue = $select.val();

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $.each(response.items || [], function (index, item) {
                    if ($select.find("option[value='" + item.id + "']").length === 0) {
                        let option = new Option(item.text, item.id, false, false);
                        $(option).attr('data-designation', item.designation ?? '');
                        $select.append(option);
                    }
                });

                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                $select.select2({
                    placeholder: placeholderText,
                    allowClear: true,
                    width: '100%',
                    templateResult: renderOption,
                    templateSelection: function (item) {
                        return item.text || item.id;
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    }
                });

                if (currentValue) {
                    $select.val(currentValue).trigger('change');
                }
            },
            error: function (xhr) {
                console.log('AJAX error:', xhr.responseText);
            }
        });
    }

    loadSelectOptions('#approver', "{{ url('api/get_approvers') }}", 'Select Approver');
    loadSelectOptions('#manager', "{{ url('api/get_approvers') }}", 'Select Manager');
    loadSelectOptions('#division_manager', "{{ url('api/get_approvers') }}", 'Select Division Manager');
});
</script>
@endpush