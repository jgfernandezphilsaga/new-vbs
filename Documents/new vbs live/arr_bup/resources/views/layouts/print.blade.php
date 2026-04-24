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

        <!-- FontAwesome and Bootstrap -->
        <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"> -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.css" rel="stylesheet"> -->
        <link href="{{ asset('assets/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/fontawesome/css/brands.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/fontawesome/css/solid.css') }}" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

        <!-- Navbar -->
        <link href="{{ asset('css/dashboard/nav.css') }}" rel="stylesheet">
        
        <!-- Dashboard -->
        <link href="{{ asset('css/dashboard/cards.css') }}" rel="stylesheet"> <!-- Header Cards CSS -->
        <link href="{{ asset('css/dashboard/table.css') }}" rel="stylesheet"> <!-- Table CSS -->
        <link href="{{ asset('css/dashboard/status.css') }}" rel="stylesheet"> <!-- Button CSS -->
        
        <!-- Modal -->
        <link href="{{ asset('css/dashboard/buttons.css') }}" rel="stylesheet"> <!-- Button CSS -->

        <!-- SEO CSS -->    
    	<link href="{{ asset('css/seo/seo.css') }}" rel="stylesheet" type="text/css"/>

        <!-- Canvas Assets -->
        <link href="{{ asset('canvas/seo/seo.css') }}" rel="stylesheet">

        <!-- DataTables -->
        <link href="{{ asset('assets/datatables/datatables.min.css') }}" rel="stylesheet">

        <!-- DateRange Picker -->
        <link href="{{ asset('assets/daterangepicker/daterangepicker.css') }}" rel="stylesheet">

        <!-- Styles -->
        <style>
            body { 
                font-family: 'Poppins', sans-serif !important; 
                font-size: 12px;
                text-size-adjust: auto;
                margin: 0px;
            }

            a {
                text-decoration:none;
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
                font-weight: 700;
                
                display: flex;
                align-items: center;
                padding: 5px 15px;
                letter-spacing: px;
            }

            .navbar-items > ul > li:hover{
                color: rgb(254, 150, 3);
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
                transform: rotate(10deg); 
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

            /* .request-table > td > * {
                width:fit-content;
            } */
        </style>
    </head>

    <!-- SCRIPTS -->

    <!-- jQuery Scripts -->
    <script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>

    <!-- DataTables Scripts -->
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            table = new DataTable('#testTable');
        });
    </script>
        
    <!-- Canvas -->
    <script src="{{ asset('canvas/js/jquery.js') }}" > </script>
    <script src="{{ asset('canvas/js/plugins.min.js') }}" > </script>
    
    <!-- DateRange Picker -->
    <script src="{{ asset('assets/daterangepicker/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/daterangepicker/daterangepicker.js') }}" ></script>

    <body class="antialiased">
        <!-- PRINT -->
        @include('admin.print.print-request')
        
        <div class="content">
            <!-- TOAST ALERTS -->        
            @include('admin.components.alert')
            
            <!-- CONTENT  -->
            @yield('content')
        </div>

        
    </body>
</html>
