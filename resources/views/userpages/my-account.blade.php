@extends('userpages.sidebar')

@section('content')
    <div class="container-fluid px-4" >
        @include('sweetalert::alert')
        <div class="row mt-5">
            <div class="col">
                <h1><b>My Account Details</b>
                    @if(Auth::user()->user_role == 3)
                        <a href="{{ route('edit.oic.account', Auth::user()) }}" title="Edit Account" class="btn btn-light ml-1">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                    @endif
                </h1>
            </div>
            <div class="col text-right">
                <input type="button" data-toggle="modal" data-target="#change-password" class="btn btn-primary" value="Change Password">
            </div>
        </div>      
        <hr>
        <div class="row">
            <div class="col-lg d-flex">
                <label class="title-detail">Last Name : </label>
                <p class="detail-value">{{Auth::user()->last_name}}</p>
            </div>
            <div class="col-lg d-flex">
               <label class="title-detail">Birthday :</label>
                <p class="detail-value">{{date('F d, Y', strtotime(Auth::user()->birthday))}}</p>
            </div>
            <div class="col-lg d-flex">
                <label class="title-detail">Phone No.</label>
                <p class="detail-value">{{Auth::user()->phone_number}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 d-flex">
                <label class="title-detail">First Name :</label>
                <p class="detail-value">{{Auth::user()->first_name}}</p>
               
            </div>
            <div class="col-lg-4 d-flex">
                <label class="title-detail">Sex :</label>        
                <p class="detail-value">{{Auth::user()->sex}}</p>
            </div>
            <div class="col-lg-4 d-flex">
                <label class="title-detail">Email :</label>        
                <p class="detail-value"> {{Auth::user()->email}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 d-flex">
                <label class="title-detail">Middle Name :</label>
                <p class="detail-value">{{(Auth::user()->middle_name == null)? "None" : Auth::user()->middle_name}}</p>
            </div>
            <div class="col-lg-8 d-flex">
                <label class="title-detail">Civil Status :</label>
                <p class="detail-value">{{Auth::user()->civil_status}}</p>
            </div>
         
        </div>
        <div class="row">
            <div class="col-lg-4 d-flex">
                <label class="title-detail">Suffix :</label>
                <p class="detail-value">{{(Auth::user()->suffix == null)? "None" : Auth::user()->suffix}}</p>
            </div>
            <div class="col-lg-8 d-flex">
                <label class="title-detail">Address :</label>
                <p class="detail-value">{{Auth::user()->address}}</p>
            </div>
        </div>
        <div class="modal" id="change-password">
            <div class="modal-dialog">
                <div class="modal-content" >
                    <div class="modal-header">
                        <h5 class="modal-title">Change password</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('change.password') }}" method="POST">
                            @csrf
                            <div class="w-75 m-auto">
                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <label class="title-detail">Current Password : </label>
                                        <input type="password" name="current_password" class="form-control password" value="{{ old('current_password') }}" required>
                                        @if ($errors->has('current_password'))
                                            <span class="text-danger">{{ $errors->first('current_password') }}</span>
                                        @endif
                                    </div>                              
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <label class="title-detail">New Password : </label>
                                        <input type="password" name="new_password" class="form-control password" value="{{ old('new_password') }}" required>
                                        @if ($errors->has('new_password'))
                                            <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <label class="title-detail">Confirm Password : </label>
                                        <input type="password" name="confirm_password" class="form-control password" required>
                                        @if ($errors->has('confirm_password'))
                                            <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex mt-2">
                                    <input type='checkbox' id='show-password'/>
                                    <p style="margin-bottom: -1px; "class="p-1"> Show password</p>
                                </div>       
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#show-password").click(function(){
                if("password"== $(".password").attr("type")){
                    $(".password").prop("type", "text");
                }else{
                    $(".password").prop("type", "password");
                }
            });
        });
    </script>
@endsection