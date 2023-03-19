@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
    @if(Auth::user()->user_role == 3)
        <button onclick="window.location='{{ url()->previous() }}';" class="btn btn-primary buttons mb-4">Back</button>
    @else
        <button onclick="window.location='{{ route('view.account', $user->user_id) }}';" class="btn btn-primary buttons mb-4">Back</button>
    @endif
    <div class="container-fluid px-4">
        @if(Auth::user()->user_role == 3)
            <form action="{{ route('edit.oic.account.save', $user->user_id) }}" class="m-4" method="POST">
        @else
            <form action="{{ route('edit.account.save', $user->user_id) }}"  method="POST">
        @endif
            @csrf
            <h1><b>Edit account</b></h1><hr>
            <div class="row">
                <div class="col-xl-3 mt-3">
                    <label class="title-detail" for="last-name">Last Name :</label>
                    <input type="text" id="last-name" name="last_name" class="form-control" placeholder="Last name" value="{{ $user->last_name }}">
                    @if ($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
                <div class="col-xl-4 mt-3">
                    <label class="title-detail">First name :</label>
                    <input type="text" name="first_name" class="form-control" id="email" placeholder="First name" value="{{ $user->first_name }}">
                    @if ($errors->has('first_name'))
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>
                <div class="col-xl-3 mt-3">
                    <label class="title-detail">Middle Name :</label>
                    <input type="text" name="middle_name" class="form-control" placeholder="Middle name" value="{{ $user->middle_name }}">
                    @if ($errors->has('middle_name'))
                    <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                    @endif
                 
                </div>
                <div class="col-xl-2 mt-3">
                    <label class="title-detail">Suffix :</label>
                    <input type="text" name="suffix" class="form-control" placeholder="Suffix" value="{{ $user->suffix }}">
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 mt-3">
                    <label class="title-detail">Date of Birth :</label>
                    <input type="date" class="form-control" id="birthday-applicant" name="birthday" value="{{ $user->birthday }}">
                    @if ($errors->has('birthday'))
                    <span class="text-danger">{{ $errors->first('birthday') }}</span>
                    @endif
                </div>
                <div class="col-xl-2 mt-3">
                    <label class="title-detail">Sex :</label>
                    <select name="sex" class="form-control">
                        <option selected disabled value="">Please select a Sex</option>
                        <option value="Male" class="sex" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>  
                        <option value="Female" class="sex" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @if ($errors->has('sex'))
                        <span class="text-danger">{{ $errors->first('sex') }}</span>
                    @endif

                </div>
                <div class="col-xl-3 mt-3">
                    <label class="title-detail">Civil Status :</label>
                    <select name="civil_status" class="form-control">
                        <option selected disabled value="" >Select a civil status</option>
                        <option value="Single" class="civil-status" {{old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" class="civil-status" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Widowed" class="civil-status" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                    @if ($errors->has('civil_status'))
                    <span class="text-danger">{{ $errors->first('civil_status') }}</span>
                    @endif
                   
                </div>
                <div class="col-xl-4 mt-3">
                    <label class="title-detail">Phone Number :</label>
                    <input type="text" class="form-control" placeholder="Enter cellphone number" name="phone_number" minlength="11" maxlength="11" value="{{ $user->phone_number }}">
                    @if ($errors->has('phone_number'))
                    <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                    @endif
                </div>

                
            </div>
            <div class="row">
                <div class="col-xl-8 mt-3">
                    <label class="title-detail">Address :</label>
                    <input type="text" class="form-control" placeholder="Enter Address" name="address" value="{{ $user->address }}">
                    @if ($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                    @endif
                   
                </div>
                <div class="col-xl-4 mt-3">
                    <label class="title-detail">Email :</label>
                    <input type="text" class="form-control" placeholder="Enter Address" name="email" value="{{ $user->email }}">
                    @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif          
                </div>
            </div><br>
            <div class="row">
                <div class="col-xl-12 text-right">
                    @if(Auth::user()->user_role == 3)
                        <button type="submit" class="btn btn-success buttons">Save Changes</button>
                    @else
                        <input type="submit" value="Save Changes" class="btn btn-success buttons">
                    @endif
                </div>
            </div>
        </form>
    </div>

<script>
        $(document).ready(function(){
            var sex = $('.sex');
            for(let i = 0; i <= sex.length; i++){    
                let sexIndex = String(sex[i].innerHTML);
                if(sexIndex == {!! json_encode($user->sex) !!}){
                    $('.sex').eq(i).attr('selected', '');
                    break;
                }
            }
            var civilStatus = $('.civil-status');
            for(let i = 0; i <= civilStatus.length; i++){    
                let civilStatusIndex = String(civilStatus[i].innerHTML);
                if(civilStatusIndex == {!! json_encode($user->civil_status) !!}){
                    $('.civil-status').eq(i).attr('selected', '');
                    break;
                }
            }
        });
    </script>
@endsection