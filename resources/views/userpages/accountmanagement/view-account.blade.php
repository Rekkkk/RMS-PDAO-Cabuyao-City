@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
       @if(Auth::user()->user_role == 3)
            <button onclick="window.location='{{ route('account.management.oic') }}';" class="btn btn-primary buttons mb-4">Back</button>
        <div class="container-fluid px-4" >
        <div class="row">

            <div class="col-lg text-right">
                <div class="dropdown dropleft float-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Options
                    </button>
                    <div class="dropdown-menu text-center">
                        @if($user->userStatus->is_disable == 1 || ($user->userStatus->is_suspend == 1 && $user->userStatus->suspend_start < date('Y-m-d')))
                            <a class="dropdown-item" onclick="return confirm('Are you sure to enable this account ?')" href="{{ route('enable.oic.account', $user->user_id) }}">Enable Account</a>
                        @elseif($user->userStatus->is_suspend == 1 && $user->userStatus->suspend_start > date('Y-m-d')) 
                            <a class="dropdown-item" onclick="return confirm('Are you sure to cancel temporary disable for this account ?')" href="{{ route('enable.oic.account', $user->user_id) }}">Cancel Disable Temp.</a>
                        @else
                            <a class="dropdown-item" data-toggle="modal" data-target="#suspend-account" href="">Disable Temporary</a>
                            <a class="dropdown-item" onclick="return confirm('Are you sure to disable this account ?')" href="{{route('disable.oic.account', $user->user_id)}}">Disable Account</a>
                        @endif
                        <a class="dropdown-item" data-toggle="modal" data-target="#reset-password" href="">Reset Password </a>
                    </div> 
                </div>                
            </div>
        </div>   
    @else
        <button onclick="window.location='{{ route('account.management') }}';"  class="btn btn-primary buttons mb-4">Back</button>
        <div class="container-fluid px-4" >
        <div class="row">
            <div class="col-lg text-right">
                <div class="dropdown dropleft float-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Options
                    </button>
                    <div class="dropdown-menu text-center">
                        @if($user->userStatus->is_disable == 1)
                            <a class="dropdown-item" onclick="return confirm('Are you sure to enable this account ?')" href="{{ route('enable.account', $user->user_id) }}">Enable Account</a>
                        @elseif($user->userStatus->is_suspend == 1 && $user->userStatus->suspend_start > date('Y-m-d')) 
                            <a class="dropdown-item" onclick="return confirm('Are you sure to cancel temporary disable for this account ?')" href="{{ route('enable.account', $user->user_id) }}">Cancel Disable Temp.</a>
                        @else
                            <a class="dropdown-item" data-toggle="modal" data-target="#suspend-account" href="">Disable Temporary</a>
                            <a class="dropdown-item" onclick="return confirm('Are you sure to disable this account ?')" href="{{route('disable.account', $user->user_id)}}">Disable Account</a>
                        @endif
                        <a class="dropdown-item" data-toggle="modal" data-target="#reset-password" href="">Reset Password </a>
                    </div> 
                </div>                
            </div>
        </div>   
    @endif
    <div class="row">
        <div class="col-lg-8">
            <h1><b>Account Details</b>
                @if(Auth::user()->user_role == 3)
                    <a href="{{ route('edit.oic.account', $user) }}" title="Edit Account" class="btn btn-info ml-1">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>
                @else
                    <a href="{{ route('edit.account', $user) }}" title="Edit Account" class="btn btn-info ml-1">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>
                @endif
                
            </h1>
                <span class="h5">Account Status : 
                @if($user->userStatus->is_disable == 1)
                <span class="text-danger h6">Inactive </span>  
                @elseif($user->userStatus->is_suspend == 1 && $user->userStatus->suspend_start < date('Y-m-d'))
                <span class="text-danger h6">Inactive Temporarily until {{ date('F d, Y', strtotime($user->userStatus->suspend_end)) }} </span>  
                @else
                <span class="text-success h6">Active 
                    @if($user->userStatus->is_suspend == 1 && $user->userStatus->suspend_start > date('Y-m-d'))
                        <span class="text-body h6">(Inactive Temporarily starting {{ date('F d, Y', strtotime($user->userStatus->suspend_start)) }} )</span>  
                    @endif
                </span>  
                @endif
            </span>
            
        </div>
        <div class="col-lg-4 text-right mt-5">
            @if($user->is_new_account == 1)
                @if(Auth::user()->user_role == 3)
                    <a class="h6" id="print-auth" href="{{ route('dowload.oic.auth' , $user) }}" >Print authenticated details</a>
                @else
                    <a class="h6" id="print-auth" href="{{ route('dowload.auth' , $user) }}" >Print authenticated details</a>
                @endif
            @endif
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg d-flex">
            <label class="title-detail">Last Name : </label>
            <p class="detail-value">{{$user->last_name}}</p>
        </div>
        <div class="col-lg d-flex">
           <label class="title-detail">Birthday :</label>
            <p class="detail-value">{{date('F d, Y', strtotime($user->birthday))}}</p>
        </div>
        <div class="col-lg d-flex">
            <label class="title-detail">Phone No.</label>
            <p class="detail-value">{{$user->phone_number}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 d-flex">
            <label class="title-detail">First Name :</label>
            <p class="detail-value">{{$user->first_name}}</p>
           
        </div>
        <div class="col-lg-4 d-flex">
            <label class="title-detail">Sex :</label>        
            <p class="detail-value">{{$user->sex}}</p>
        </div>
        <div class="col-lg-4 d-flex">
            <label class="title-detail"> Email : </label>
            <p class="detail-value">{{$user->email}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 d-flex">
            <label class="title-detail">Middle Name :</label>
            <p class="detail-value">{{($user->middle_name == null)? "None" : $user->middle_name}}</p>
        </div>
        <div class="col-lg-4 d-flex">
            <label class="title-detail">Civil Status :</label>
            <p class="detail-value">{{$user->civil_status}}</p>
        </div>
        <div class="col-lg-4 d-flex">
            @if(Auth::user()->user_role == 3)
            <label class="title-detail" > Position : </label>
                <p class="detail-value">Officer In Charge</p>
            @else
            <label class="title-detail" > Handle Barangay or Position : </label>
                <p class="detail-value">{{ ($user->barangay->barangay_name == null)? "Officer In Charge" : $user->barangay->barangay_name}}</p>
            @endif
        </div>
     
    </div>
    <div class="row">
        <div class="col-lg-4 d-flex">
            <label class="title-detail">Suffix :</label>
            <p class="detail-value">{{($user->suffix == null)? "None" : $user->suffix}}</p>
        </div>
        <div class="col-lg-8 d-flex">
            <label class="title-detail">Address :</label>
            <p class="detail-value">{{$user->address}}</p>
        </div>
    </div>

    <div class="modal fade" id="suspend-account">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Disable Account Temporarily</h5>
                        <button type="button" class="close" data-dismiss="modal" >&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="w-75 m-auto">
                            @if(Auth::user()->user_role == 3)
                            <form action="{{ route('suspend.oic.account', $user->user_id) }}" method="post">
                            @else
                            <form action="{{ route('suspend.account', $user->user_id) }}" method="post">
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <label class="title-detail">Start Disable start:</label>
                                        <input type="date" class="form-control" name="suspend_start" >
                                        @if ($errors->has('suspend_start'))
                                        <span class="text-danger">{{ $errors->first('suspend_start') }}</span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="title-detail">End Disable date :</label>
                                        <input type="date" class="form-control" name="suspend_end" >
                                        @if ($errors->has('suspend_end'))
                                        <span class="text-danger">{{ $errors->first('suspend_end') }}</span>
                                        @endif
                                    </div>
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

    <div class="modal fade" id="reset-password">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reset Password</h4>
                        <button type="button" class="close" data-dismiss="modal" >&times;</button>
                    </div>          
                    <div class="modal-body">
                        <div class="w-75 m-auto">
                            @if(Auth::user()->user_role == 3)
                            <form action="{{ route('reset.oic.password', $user->user_id) }}" method="POST">
                            @else
                            <form action="{{ route('reset.password', $user->user_id) }}" method="POST">
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="password" class="title-detail">New Password:</label>
                                    </div> 
                                            
                                </div>   
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control password" id="password" name="password" minlength="8" value="{{ old('password') }}" required>
                                    </div> 
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <label for="confirm-password" class="title-detail">Confirm Password:</label>  
                                    </div>
                                    
                                </div>    
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control password" name="confirm_password" id="confirm-password" required>
                                        @if ($errors->has('confirm_password'))
                                            <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                        @endif    
                                    </div>
                                   
                                </div> 
                                <div style="display: flex">
                                    <input type='checkbox' id='show-password'/>
                                    <p style="margin-bottom: -1px; "class="p-1"> Show password</p>
                                </div>                 
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                            <input type="submit" value="Save Changes" id="change-password" class="btn btn-success buttons">
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