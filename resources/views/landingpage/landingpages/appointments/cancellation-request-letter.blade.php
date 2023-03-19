@extends('landingpage.navigation.navigation')

@section('content')
@include('sweetalert::alert')
    <div class="container mt-5">
        <div class="row mt-5">
            <div class="col text-center">
                <h1><b>Appointment for PWD Cancellation</b></h1>
                <label class="text-danger">*Note : You can only upload pdf scan copy of documents </label>
            </div>
        </div>
        @if(session()->has('message'))
            <div class="alert alert-danger" id="message">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="m-auto py-4 form-container" style="width : 500px; max-width: 80%;">
            <form action="{{ route('cancellation.create') }}" class="m-auto" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <label class="title-detail">Available Appointment Dates</label>
                        <span class="text-danger">* required</span>
                        <input type="text" class="form-control" id="datepicker-appointment" name="appointment_date"placeholder="Click to choose date" required readonly="false">
                        @if ($errors->has('appointment_date'))
                            <span class="text-danger">{{ $errors->first('appointment_date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <label class="title-detail">PWD Number </label>
                        <span class="text-danger">* required</span>
                        <input type="text" class="form-control" name="pwd_id" placeholder="PWD Number" value="{{ old('pwd_id') }}" required>
                        @if ($errors->has('pwd_id'))
                            <span class="text-danger">{{ $errors->first('pwd_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <label class="title-detail">Scan Copy of PWD ID</label>
                        <span class="text-danger">* required</span>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="Id[]" accept="application/pdf" required multiple >
                            <label class="custom-file-label" for="customFile">Upload scan copy</label>
                            @if ($errors->has('Id'))
                                <span class="text-danger">{{ $errors->first('Id') }}</span>
                            @endif
                        </div>
                    </div>       
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <label class="title-detail">Authorization Letter (Optional)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="authorization[]" accept="application/pdf" multiple>
                            <label class="custom-file-label" for="customFile">Upload scan copy</label>
                            @if ($errors->has('authorization'))
                                <span class="text-danger">{{ $errors->first('authorization') }}</span>
                            @endif                            
                        </div>
                    </div>      
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 text-right">
                        <input type="submit" style="" value="Submit" class="btn btn-success buttons">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection