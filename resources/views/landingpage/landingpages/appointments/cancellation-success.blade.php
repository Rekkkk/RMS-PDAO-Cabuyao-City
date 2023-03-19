@extends('landingpage.navigation.navigation')

@section('content')
@include('sweetalert::alert')
<div class="container">
    <div class="w-75 m-auto ">
        <div class="row " >
            <div class="col-12 text-center mt-5">
                <h1><b>Appointment for Request Cancellation</b></h1>
            </div>
        </div><br>
        <div class="row">
            <div class="col-12 text-right">
                <a href="{{ route('print.appointment', $appointment) }}" class="btn btn-primary">
                    <i class="fa fa-download"></i>
                    <span>DOWNLOAD</span>
                </a>
            </div>
        </div>
        <div class="row ">
            <div class="col-12">
                <h3>Your Reference Number =  <b>RCL{{ date('j', strtotime($appointment->appointment_date )) }}-0{{$appointment->barangay_id}}-{{$appointment->appointment_id}}0</b> </h3>
            </div>
          
        </div>
        <div class="row ">
            <div class="col-12">
                <h3>Date Of Appointment = <b>{{ date('F j, Y', strtotime($appointment->appointment_date )) }}</b></h3>
            </div>
        </div><br>
        <div class="row ">
            <div class="col-12">
                <h2><b>Please bring the following !</b></h2>
            </div>
         
        </div>
        <div class="row ">
            <ol>
                <li class="h5">PWD ID</li>
                <li class="h5">Authorization Letter sa PWD na di sila ng mag proprocesso</li>
           </ol>
        </div>
    </div>
        
</div>
@endsection