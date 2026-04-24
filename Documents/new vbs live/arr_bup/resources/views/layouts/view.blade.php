<!-- @extends('layouts.app') -->
@extends('layouts.app')

@section('test')
    <!-- HEADER -->
    @include('admin.components.nav')

        <div class="content">
            <!-- TOAST ALERTS -->        
            @include('admin.components.alert')
            
            <!-- CONTENT  -->
            @yield('content')
        </div>
@endsection