@extends('userpages.sidebar')

@section('content')
    <a href="{{ route('appointment.page') }}"  class="btn btn-primary buttons m-1">Back</a>

    <div class="container-fluid px-4 mt-4 mb-4 ">
        <div class="row mb-4">
            <div class="col-lg-12">
                <h1><b>All Appointments</b></h1>
            </div>
        </div>
        <table id="appointment-list" class="table table-hover" style="width:100%" >
            <thead>
                <tr>
                    <th>Reference No.</th>
                    <th>Transaction</th>
                    <th>Name</th>
                    <th>Appointment Date</th>    
                    <th>Appointment Status</th>    
                </tr>
            </thead>
            <tbody id="myTable" style="cursor: pointer">
                @foreach($appointment as $appointments)
                <tr onclick="window.location='{{ route('select.appointment', $appointments->appointment_id) }}';">
                    <td>
                        @if($appointments->transaction == "Application")
                            APL{{ date('j', strtotime($appointments->appointment_date )) }}-0{{$appointments->barangay_id}}-{{$appointments->appointment_id}}0
                        @elseif($appointments->transaction == "Renewal ID")
                            RNW{{ date('j', strtotime($appointments->appointment_date )) }}-0{{$appointments->barangay_id}}-{{$appointments->appointment_id}}0
                        @elseif($appointments->transaction == "Lost ID")
                            LST{{ date('j', strtotime($appointments->appointment_date )) }}-0{{$appointments->barangay_id}}-{{$appointments->appointment_id}}0
                        @else
                            RCL{{ date('j', strtotime($appointments->appointment_date )) }}-0{{$appointments->barangay_id}}-{{$appointments->appointment_id}}0
                        @endif
                    </td>
                    <td>{{$appointments->transaction}}</td>
                    <td>
                        @if($appointments->transaction == "Application")
                            {{ $appointments->applicant->last_name . ", ".  $appointments->applicant->first_name . " ".  $appointments->applicant->middle_name}}
                        @elseif($appointments->transaction == "Renewal ID")
                            {{ $appointments->renewal->pwd->last_name . ", ".  $appointments->renewal->pwd->first_name . " ".  $appointments->renewal->pwd->middle_name}}
                        @elseif($appointments->transaction == "Lost ID")
                            {{ $appointments->lostId->pwd->last_name . ", ".  $appointments->lostId->pwd->first_name . " ".  $appointments->lostId->pwd->middle_name}}
                        @else
                            {{ $appointments->cancellation->pwd->last_name . ", ".  $appointments->cancellation->pwd->first_name . " ".  $appointments->cancellation->pwd->middle_name}}
                        @endif
                    </td>

                    {{-- <td>{{$appointments->transaction}}</td> --}}



                    <td>{{date('F d, Y', strtotime($appointments->appointment_date))}}</td>
                    @if ($appointments->appointment_status == "Done")
                    <td class="text-success" >{{$appointments->appointment_status}}</td>
                    @elseif($appointments->appointment_status == "Pending")
                    <td class="text-info" >{{$appointments->appointment_status}}</td>
                    @else
                    <td class="text-danger" >{{$appointments->appointment_status}}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table> 
        <br>
    </div>
    <script>
         $(document).ready(function () {
            $('#appointment-list').DataTable();
        });
    </script>
@endsection