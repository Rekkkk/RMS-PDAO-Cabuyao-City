@extends('userpages.sidebar')

@section('content')

<div class="container mt-5">
    <form action="{{ route('change.password') }}" method="POST">
        @csrf
        <div class="m-auto" style="width: 360px">
            <h4 class="text-center"><b>Please Change your Password</b></h4>
            <p class="text-danger text-center" style="font-size: 11px">Note: You cannot perform of any process by using default password !</p>
            <div class="row mt-2">
                <div class="col-lg-12">
                    <label class="modal-text">Current Password : </label>
                    <input type="password" name="current_password" class="form-control password" value="{{ old('current_password') }}">
                    @if ($errors->has('current_password'))
                        <span class="text-danger">{{ $errors->first('current_password') }}</span>
                    @endif
                </div>                              
            </div>
            <div class="row mt-2">
                <div class="col-lg-12">
                    <label class="modal-text">New Password : </label>
                    <input type="password" name="new_password" class="form-control password" value="{{ old('new_password') }}">
                    @if ($errors->has('new_password'))
                        <span class="text-danger">{{ $errors->first('new_password') }}</span>
                    @endif
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-12">
                    <label class="modal-text">Confirm Password : </label>
                    <input type="password" name="confirm_password" class="form-control password">
                    @if ($errors->has('confirm_password'))
                        <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                    @endif
                </div>
            </div>
            <div class="d-flex mt-2">
                <input type='checkbox' id='show-password'/>
                <p style="margin-bottom: -1px; "class="p-1"> Show password</p>
            </div>  
            
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-success ">Save Changes</button>
                </div>
            </div>
        </div>
    </form>    
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

