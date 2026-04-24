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
        <!-- <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.css') }}" rel="stylesheet"> -->
        <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- FontAwesome -->
        <link href="{{ asset('assets/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/fontawesome/css/brands.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/fontawesome/css/solid.css') }}" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('css/forms/forms.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/forms/forms.css') }}" rel="stylesheet">

        <link href="{{ asset('css/dashboard/cards.css') }}" rel="stylesheet"> <!-- Header Cards CSS -->
        <link href="{{ asset('css/dashboard/table.css') }}" rel="stylesheet"> <!-- Table CSS -->
        <link href="{{ asset('css/dashboard/status.css') }}" rel="stylesheet"> <!-- Button CSS -->
        <link href="{{ asset('css/modal/modal.css') }}" rel="stylesheet"> <!-- Modal CSS -->

        <!-- Navbar -->
        <link href="{{ asset('css/dashboard/nav.css') }}" rel="stylesheet">
        
        <!-- Modal -->
        <link href="{{ asset('css/dashboard/buttons.css') }}" rel="stylesheet"> <!-- Button CSS -->

        <!-- SEO CSS -->    
    	<link href="{{ asset('css/seo/seo.css') }}" rel="stylesheet" type="text/css"/>

        <!-- Canvas Assets -->
        <link href="{{ asset('canvas/seo/seo.css') }}" rel="stylesheet">

        <!-- DataTables -->
        <link href="{{ asset('assets/datatables-test/datatables.min.css') }}" rel="stylesheet">
         
        <!-- DateRange Picker -->
        <link href="{{ asset('assets/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/flatpickr.min.css') }}" rel="stylesheet">
        
        <!-- Select2 -->
        <link href="{{ asset('assets/select2/dist/css/select2.min.css') }}" rel="stylesheet">
        
        <!-- Styles -->
        <style>
            @font-face {
                font-family: 'Poppins';
                /*src: 
                    url('/fonts/your-font-file.ttf') format('truetype');*/
                font-weight: normal;
                font-style: normal;
            }

            body { 
                font-family: 'Poppins', sans-serif !important; 
                font-size: 12px;
                text-size-adjust: auto;
                margin: 0px;
            }

            a {
                /* text-decoration:none; */
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
                /* font-family: 'Poppins', sans-serif !important; */
                /* box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 10px 0px; */ 
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
                /* font-weight: 700; */
                
                display: flex;
                align-items: center;
                padding: 5px 15px;
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

            .input-container {
                position: relative;
            }

            .input-container.invalid::after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 0, 0, 0.3); /* Red color with 30% opacity */
                pointer-events: none; /* Make sure overlay doesn't interfere with interactions */
                border: 1px solid red; /* Optional: border to emphasize the error */
            }

        </style>

        <!-- YIELDED STYLES -->
        @yield('styles')
    </head>

    <!-- SCRIPTS -->

    <!-- jQuery Scripts -->
    <script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/datatables-test/datatables.min.js') }}"></script>

    <!-- Bootstrap Scripts -->
    <script src="{{ asset('assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Canvas -->
    <!-- <script src="{{ asset('canvas/js/jquery.js') }}" > </script>
    <script src="{{ asset('canvas/js/plugins.min.js') }}" > </script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->

    <!-- DataTables -->
    
   
    <!-- Select2 -->
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    
    <!-- Moment JS -->
    <script src="{{ asset('assets/momentjs/moment.js') }}" ></script>

    <!-- DateRange Picker -->
    <script src="{{ asset('assets/daterangepicker/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/daterangepicker/daterangepicker.js') }}" ></script>
    <script src="{{ asset('assets/flatpickr.js') }}" ></script>

    <body class="antialiased">
        <!-- HEADER -->
        @include('admin.components.nav')
        
        <div class="content">
            <!-- TOAST ALERTS -->        
            @include('admin.components.alert')
            
            <!-- CONTENT  -->
            @yield('content')
        </div>
    </body>
</html>
