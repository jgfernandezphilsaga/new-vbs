@extends('layouts.app')

@section('content')
<div class="" style="display: flex; justify-content: center; align-items: center;  min-height: 80vh;"> <!-- class="d-flex flex-column justify-content-center align-items-center" -->
    <div class="card" style="width: 60vw">
        <div class="card-title">
            <div class="mb-2" style="display:flex; flex-direction:row; align-items:flex-start;">
                <div class="d-flex flex-row mt-3 mx-auto">
                    <h5 style="font-weight: bold">Create New User</h1>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column card">
            <form autocomplete="off" method="post" action="{{route('user.store')}}">
                @csrf
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-5 form-group">
                            <label for="full-name">Full Name<span style="color:red"> *</span></label>
                            <input id="full-name" class="form-control" name="full_name" type="text" required/> 
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="dept">Department<span style="color:red"> *</span></label>
                            <input id="dept" class="form-control" name="dept" type="text" required/> 
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="role">Role<span style="color:red"> *</span></label>
                            <!-- <input id="role" class="form-control" name="role" type="text" required/>  -->
                             <select id="role" class="form-select" name="role" aria-label="Select user's role">
                                <option value="" selected>Choose role</option>
                                <option value="dept_secretary">Department Secretary</option>
                                <option value="gsd_dispatcher">GSD Dispatcher</option>
                                <option value="gsd_manager">GSD Manager</option>
                             </select>
                        </div>
                        <!-- <div class="col-md-4 form-group">
                            <label for="first-name">First Name<span style="color:red"> *</span></label>
                            <input id="first-name" class="form-control" name="first_name" type="text" required/> 
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="middle-name">Middle Name</label>
                            <input id="middle-name" class="form-control" name="middle_name" type="text" /> 
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="last-name">Last Name<span style="color:red"> *</span></label>
                            <input id="last-name" class="form-control" name="last_name" type="text" required/> 
                        </div> -->
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
                            <input id="email" class="form-control" name="email" type="text" /> 
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <!-- <div class=""> -->
                        <button id="cancel-form" class="btn btn-danger" type="button" onclick="history.back()"><i class="fa-solid fa-circle-xmark"></i> Cancel</button>
                        <button id="submit-form" class="btn btn-success" type="submit"><i class="fa-solid fa-paper-plane"></i> Submit</button>
                    <!-- </div> -->
                </div>
            </form>
        </div>

    </div>
</div>
@endsection