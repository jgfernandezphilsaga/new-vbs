<div class="toast-container" style="right: 5%;">
    <div class="toast text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true" id="success-toast" autohide="true">
        <div class="toast-header">
            <strong class="me-auto">Success!</strong>
            <small class="text-muted">just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="success-toast-body"></div>
    </div>

    <div class="toast text-white bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="error-toast" autohide="true">
        <div class="toast-header">
            <strong class="me-auto">Error!</strong>
            <small class="text-muted">just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="error-toast-body"></div>
    </div>
</div>

<script>
    var successToast = new bootstrap.Toast(document.getElementById('success-toast'));
    var errorToast = new bootstrap.Toast(document.getElementById('error-toast'));
    var bigErrorToast = new bootstrap.Toast(document.getElementById('error-toast'));

    function triggerToast(type, message) {
        switch(type) {
            case 'success':
                $('#success-toast-body').text(message);
                successToast.show();
                break;
            case 'error':
                $('#error-toast-body').text(message);
                errorToast.show();
                break;
            default:
                break;
        }
    }
</script>

@if(Session::get('success'))
    <script>
        $('#success-toast-body').text("{!! Session::get('success') !!}");
        successToast.show();
    </script>
@endif

@if(Session::get('error'))
    <script>
        $('#error-toast-body').text("{!! Session::get('error') !!}");
        errorToast.show();
    </script>
@endif

<!-- Make larger Toast -->
@if(Session::get('errorMultiple'))
    <script>
        bigErrorToast.show();
    </script>
@endif