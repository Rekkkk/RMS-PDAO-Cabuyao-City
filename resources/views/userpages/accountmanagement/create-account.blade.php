@extends('userpages.sidebar')

@section('content')
    <style>
        #select-barangay-div{
            display: none;
        }
        label{
          font-size: 15px;
          font-weight: 700;
          margin-bottom: -1px;
    }
    </style>
    @if(Auth::user()->user_role == 3)
        <button onclick="window.location='{{ route('account.management.oic') }}';" class="btn btn-primary buttons mb-4">Back</button>
    @else
        <button onclick="window.location='{{ route('account.management') }}';" class="btn btn-primary buttons mb-4">Back</button>
    @endif
    <div class="container-fluid px-4">
      
        @if(Auth::user()->user_role == 3)
            <form action="{{ route('oic.create') }}"  method="POST">
        @else
            <form action="{{ route('account.create') }}"  method="POST">
        @endif
            @csrf
            <h1><b>Create new account</b></h1><hr>
            <div class="row">
                <div class="col-xl-3 mt-3">
                    <label for="last-name">Last Name :</label>
                    <span class="text-danger">* Required</span> 
                    <input type="text" id="last-name" name="last_name" class="form-control" placeholder="Last name" value="{{ old('last_name') }}" required>
                    @if ($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
                <div class="col-xl-4 mt-3">
                    <label>First name :</label>
                    <span class="text-danger">* Required</span> 
                    <input type="text" name="first_name" class="form-control" id="email" placeholder="First name" value="{{ old('first_name') }}" required>
                    @if ($errors->has('first_name'))
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>
                <div class="col-xl-3 mt-3">
                    <label>Middle Name :</label>
                    <input type="text" name="middle_name" class="form-control" placeholder="Middle name" value="{{ old('middle_name') }}">
                    @if ($errors->has('middle_name'))
                    <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                    @endif
                </div>
                <div class="col-xl-2 mt-3">
                    <label>Suffix :</label>
                    <input type="text" name="suffix" class="form-control" placeholder="Suffix" value="{{ old('suffix') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 mt-3">
                    <label>Date of Birth :</label>
                    <span class="text-danger">* Required</span> 
                    <input type="date" class="form-control" name="birthday" id="birthday-applicant" value="{{ old('birthday') }}" required>
                    @if ($errors->has('birthday'))
                    <span class="text-danger">{{ $errors->first('birthday') }}</span>
                    @endif
                </div>
                <div class="col-xl-2 mt-3">
                    <label>Sex :</label>
                    <span class="text-danger">* Required</span> 
                    <select name="sex" class="form-control" required>
                        <option selected disabled value="">Please a Sex</option>
                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>  
                        <option value="Female"  {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @if ($errors->has('sex'))
                        <span class="text-danger">{{ $errors->first('sex') }}</span>
                    @endif

                </div>
                <div class="col-xl-3 mt-3">
                    <label>Civil Status :</label>
                    <span class="text-danger">* Required</span> 
                    <select name="civil_status" class="form-control" required>
                        <option selected disabled value="" >Select a civil status</option>
                        <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                    @if ($errors->has('civil_status'))
                    <span class="text-danger">{{ $errors->first('civil_status') }}</span>
                    @endif
                </div>
                <div class="col-xl-4 mt-3">
                    <label>Phone Number :</label>
                    <span class="text-danger">* Required</span> 
                    <input type="text" class="form-control" placeholder="Enter cellphone number" name="phone_number" minlength="11" maxlength="11" value="{{ old('phone_number') }}" required>
                    @if ($errors->has('phone_number'))
                    <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                
                @if(Auth::user()->user_role == 2)
                    <div class="col-xl-8 mt-3">
                        <label>Address :</label>
                        <span class="text-danger">* Required</span> 
                        <input type="text" class="form-control" placeholder="Enter Address" name="address" value="{{ old('address') }}" required>
                        @if ($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                        @endif
                    </div>
                    <div class="col-xl-4 mt-3">
                        <label>Assign Barangay/Position :</label>
                        <span class="text-danger">* Required</span> 
                        <select name="barangay_id" class="form-control" required>
                            <option selected disabled value="" >Please Select</option>
                            <option value="1" {{ old('barangay_id') == 'Office Staff' ? 'selected' : '' }}>Office Staff</option>
                            @foreach($barangays as $key => $barangay)
                                @if($key > 0)
                                    <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_name ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-xl-12 mt-3">
                        <label>Address :</label>
                        <span class="text-danger">* Required</span> 
                        <input type="text" class="form-control" placeholder="Enter Address" name="address" value="{{ old('address') }}" required>
                        @if ($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                        @endif
                    </div>
                @endif
            </div><br>
            <div class="row">
                <div class="col-xl-12 text-right">        
                    <button type="submit" class="btn btn-success buttons">Create Account</button>
                </div>
            </div>
        </form>
    </div>
@endsection