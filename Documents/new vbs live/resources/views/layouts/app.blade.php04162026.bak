<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PMC | VBS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="{{ url('assets/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="{{ url('assets/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ url('assets/fontawesome/css/brands.css') }}" rel="stylesheet">
    <link href="{{ url('assets/fontawesome/css/solid.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ url('css/custom.css') }}" rel="stylesheet">
    <link href="{{ url('css/forms/forms.css') }}" rel="stylesheet">
    <link href="{{ url('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ url('css/forms/forms.css') }}" rel="stylesheet">

    <link href="{{ url('css/dashboard/cards.css') }}" rel="stylesheet">
    <link href="{{ url('css/dashboard/table.css') }}" rel="stylesheet">
    <link href="{{ url('css/dashboard/status.css') }}" rel="stylesheet">
    <link href="{{ url('css/modal/modal.css') }}" rel="stylesheet">

    <!-- Navbar -->
    <link href="{{ url('css/dashboard/nav.css') }}" rel="stylesheet">

    <!-- Modal -->
    <link href="{{ url('css/dashboard/buttons.css') }}" rel="stylesheet">

    <!-- SEO CSS -->
    <link href="{{ url('css/seo/seo.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('canvas/seo/seo.css') }}" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{ url('assets/datatables-test/datatables.min.css') }}" rel="stylesheet">

    <!-- DateRange Picker -->
    <link href="{{ url('assets/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ url('assets/flatpickr.min.css') }}" rel="stylesheet">

    <!-- Select2 -->
    <link href="{{ url('assets/select2/dist/css/select2.min.css') }}" rel="stylesheet">

    <!-- Styles -->
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('/fonts/your-font-file.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body { font-family: 'Poppins', sans-serif !important; font-size: 12px; margin: 0; }
        h1,h2,h3,h4,h5,p { margin:0; padding:0; }
        .antialiased { -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; }
        .navbar { box-shadow: 0 2px 4px 0 rgba(0,0,0,0.2); }
        .navbar-items { width:80%; margin:0 auto; display:flex; justify-content:space-between; }
        .navbar-title { font-size:25px; font-weight:bold; letter-spacing:1px; display:flex; align-items:center; padding:10px 0; }
        .navbar-items > ul { display:flex; flex-direction:row; }
        .navbar-items > ul > li { color: rgb(68,68,68); font-size:15px; display:flex; align-items:center; padding:5px 15px; }
        .content { width:80%; margin:0 auto; padding:20px 0; }
        .card { background-color: rgb(248,249,250); box-shadow:0 0.125rem 0.25rem rgba(0,0,0,0.15); text-shadow:0 0.125rem 0.25rem rgba(0,0,0,0.15); border:0; padding:0; }
        .card-icon { position:relative; top:-40px; }
        th { background-color:#E3E3E3; }
        td { vertical-align:middle; }
        .action-btn { height:18px; width:18px; overflow:hidden; padding:0; border:0; background-color:#42A5F5; color:white; align-items:center; }
        .input-container { position:relative; }
        .input-container.invalid::after { content:""; position:absolute; top:0; left:0; width:100%; height:100%; background: rgba(255,0,0,0.3); pointer-events:none; border:1px solid red; }
    </style>

    @yield('styles')
</head>
 <!-- Scripts -->
    <script src="{{ url('assets/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ url('assets/datatables-test/datatables.min.js') }}"></script>
    <script src="{{ url('assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ url('assets/momentjs/moment.js') }}"></script>
    <script src="{{ url('assets/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ url('assets/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('assets/flatpickr.js') }}"></script>
<body class="antialiased">

    @include('admin.components.nav')
    
    <div class="content">
        @include('admin.components.alert')
        @yield('content')
    </div>

   

</body>
</html>
