@extends('landingpage.navigation.navigation')

@section('content')
@include('sweetalert::alert')

<div class="container" >
    <div class="w-75 m-auto ">
        <div class="row " >
            <div class="col-12 text-center mt-5">
                <h1><b>Appointment for new Application</b></h1>
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
                <h3>Your Reference Number =  <b>APL{{ date('j', strtotime($appointment->appointment_date )) }}-{{$appointment->barangay_id}}-{{ $appointment->appointment_id }}0</b> </h3>
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
                <li class="h6">Must be a Cabuyao Resident and Registered voter of Cabuyao (Voter's ID or Voter's Certification)</li>
                <li class="h6">Medical Certificate
                    <ul>*Dapat nakasaad kung anong uri ng kapansanan</ul>
                    <ul>*Remarks ay dapat tukuyin na ang mga aplikante ay kwalipikado para sa PWD ID </ul>
                    <ul>*Ang Aplikante na may Visual Disability ay dapat magpakita ng medical certificate mula sa ophthalmologist na nagsasaad ng siya at kwalipikado para sa PWD ID</ul>
                </li>
                <li class="h6">Authorization Para sa PWD na di sila ang mag proprocess</li>
           </ol>
        </div>
    </div>
        
</div>
@endsection