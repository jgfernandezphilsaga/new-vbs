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
        <link href="{{ asset('/assets/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/fontawesome/css/brands.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/fontawesome/css/solid.css') }}" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        
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
        
        <!-- Styles -->
        <style>
            body { 
                font-family: 'Poppins', sans-serif !important; 
                font-size: 12px;
                text-size-adjust: auto;
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

            /* .login-background {
                background-image: url('assets/images/background/login.png');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            } */
        </style>
    </head>

    <!-- SCRIPTS -->
    <!-- jQuery Scripts -->
    <script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>

    <!-- Bootstrap Scripts -->
    <script src="{{ asset('assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Canvas -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->
            
    <body class="antialiased">
        
        <div class="login-background">
            <div class="content">
                <!-- TOAST ALERTS -->        
                @include('admin.components.alert')
                
                <!-- CONTENT  -->
                <div style="display: flex; justify-content: center; align-items: center;  min-height: 80vh;"> <!-- class="d-flex flex-column justify-content-center align-items-center" -->
                    <div class="card" style="width:50vw">
                        <div class="row p-3">
                            <div class="col-md-6 d-flex flex-column justify-content-center ">
                                <div style="text-align: center; margin-bottom: 5px">
                                    <img src="assets/images/logo/pmc-logo.png" style="width: 50%;" alt>
                                </div>
                                <!-- <h5 style="text-align:center">
                                    Welcome to 
                                </h5> -->
                                <h3 style="text-align:center; font-weight: bold">
                                    PMC | Vehicle Booking System
                                </h3>
                            </div>
                            <div class="col-md-6" style="border-left: 2px solid #dee2e6;">
                                <div class="p-3">
                                    <h5 style="font-weight:regular; text-align:center">Login</h5>
                                </div>
                                <div class="card-body">
                                    <form autocomplete="off" method="post" action="{{ route('login.authenticate') }}">
                                        @csrf
                                        <input class="form-control mb-2" type="text" name="username" placeholder="Username" autocomplete="off" required/>
                                        <input class="form-control mb-2" type="password" name="password" placeholder="Password" required/>
                                        <div class="d-flex flex-row justify-content-center align-items-center">
                                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-paper-plane" style="color:white"></i> Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
