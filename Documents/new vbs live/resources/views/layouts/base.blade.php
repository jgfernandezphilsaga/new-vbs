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
        <!-- <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.css') }}" rel="stylesheet"> -->
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
        <link href="{{ asset('assets/datatables-test/datatables.min.css') }}" rel="stylesheet">
        <!-- <link href="{{ asset('assets/datatables/datatables.min.css') }}" rel="stylesheet"> -->

            <!-- <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/autofill/2.7.0/css/autoFill.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/colreorder/2.0.3/css/colReorder.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/datetime/1.5.2/css/dateTime.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/fixedcolumns/5.0.1/css/fixedColumns.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/fixedheader/4.0.1/css/fixedHeader.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/keytable/2.12.1/css/keyTable.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/select/2.0.3/css/select.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/staterestore/1.4.1/css/stateRestore.bootstrap5.min.css" rel="stylesheet"> --> 
        <!-- <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.css" rel="stylesheet"> -->
       
        <!-- DateRange Picker -->
        <link href="{{ asset('assets/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
        
        <!-- Select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <!-- Styles -->
        <style>
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
                font-weight: 700;
                
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

            /* .request-table > td > * {
                width:fit-content;
            } */
        </style>
    </head>

    <!-- SCRIPTS -->

    <!-- jQuery Scripts -->
    <script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>

    <!-- Canvas -->
    <!-- <script src="{{ asset('canvas/js/jquery.js') }}" > </script>
    <script src="{{ asset('canvas/js/plugins.min.js') }}" > </script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->

    <!-- DataTables -->
    <script src="{{ asset('assets/datatables-test/datatables.min.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.js"></script> -->
    
    <!-- Select2 -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    
    <!-- DateRange Picker -->
    <script src="{{ asset('assets/daterangepicker/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/daterangepicker/daterangepicker.js') }}" ></script>

    <body class="antialiased">
        
        <div class="content">
            <!-- CONTENT  -->
            @yield('content')
        </div>
    </body>
</html>
