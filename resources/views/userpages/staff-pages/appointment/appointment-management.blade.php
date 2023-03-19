@extends('userpages.sidebar')

@section('content')
<link rel="stylesheet" href="{{ asset('/css/dashboard.css') }}">
    <div class="container-fluid px-4">
        @include('sweetalert::alert')
        <div class="row">
            <div class="col-xl-9">
                <h1><b>Appointment Management</b></h1>
            </div>
            <div class="col-xl-3 text-right">
                <div class="dropdown dropleft float-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        More Options
                    </button>
                    <div class="dropdown-menu text-center">
                        <a class="dropdown-item" href="{{ route('manage.appointment') }}">Manage Appointment</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#appointment-reports" href="#">Transaction Report</a>
                    </div> 
                </div>          
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-xl"><h4><b>{{date('F d, Y - l')}}</b></h4>
            </div>
            <div class="col-xl">
                <h6 class="text-right"><a href="{{ route('all.appointment') }}">View all appointments</a></h6>
            </div>
        </div>
        <div class="row">            
            <div class="col-sm-12 col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <div class="d-flex p-1 mb-2">
                            <h4>Appointment Today Information</h4>          
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-center">
                                <h4 class="text-c-red">{{ $allAppointment->where('appointment_date', date("Y-m-d"))->count() }}</h4>
                                <h6 class="text-muted m-b-0">Appointment Today</h6>
                            </div>
                            <div class="col-4 text-center">
                                <h4 class="text-c-red">{{ $appointment->count() }}</h4>
                                <h6 class="text-muted m-b-0">Pending Appointments</h6>
                            </div>
                            <div class="col-4 text-center">
                                <h4 class="text-c-red">{{ $appointmentProcessed }}</h4>
                                <h6 class="text-muted m-b-0">Appointment Processed</h6>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-c-red">
                        <div class="row align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table id="appointment-list" class="table table-hover" style="width:100%" >
            <thead>
                <tr>
                    <th>Reference No.</th>
                    <th>Transaction</th>
                    <th>Name</th>
                    <th>Appointment Date</th>    
                </tr>
            </thead>
            <tbody style="cursor: pointer">
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
                    <td>{{date('F d, Y', strtotime($appointments->appointment_date))}}</td>
                </tr>
              @endforeach
            </tbody>
        </table>
      <br>
  </div>
    <div class="modal fade" id="appointment-reports">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transactions Report</h5>
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <div class="modal-body">
                    <div class="m-auto">
                        <form action="{{ route('appointment.report.generate') }}" id="appointment_form" target="_blank" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <label class="title-detail">Select Barangay : </label>
                                    <select name="barangay_id" class="form-control" required>
                                        <option selected disabled value="" >Please Select Barangay</option>
                                        <option value="20" {{ old('barangay_id') == 'All Barangays' ? 'selected' : '' }}>All Barangays</option>
                                        @foreach($barangays as $key => $barangay)
                                            @if($key > 0)
                                                <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_name ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('barangay_id'))
                                        <span class="text-danger">{{ $errors->first('barangay_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label class="title-detail">Set Time Frame:</label><br> 
                                    <div class="text-center">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="statusCategory1" name="category" value="Yearly" required> 
                                            <label class="custom-control-label title-detail" for="statusCategory1">Yearly</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="statusCategory2" name="category" value="Monthly" required>
                                            <label class="custom-control-label title-detail" for="statusCategory2">Monthly</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"class="custom-control-input" id="statusCategory3" name="category" value="Quarterly" required>
                                            <label class="custom-control-label title-detail" for="statusCategory3">Quarterly</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"class="custom-control-input" id="statusCategory4" name="category" value="None" required> 
                                            <label class="custom-control-label title-detail" for="statusCategory4">None</label>
                                        </div>
                                    </div>
                                    <div id="Yearly" class="mt-2 categories">
                                        <div class="row">
                                            <div class="col-6">
                                                <select name="appointment_start_year" id="appointment_start_year" class="form-control yearpicker">
                                                    <option value="" selected disabled hidden >Select Start Year</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <select name="appointment_end_year" id="appointment_end_year" class="form-control yearpicker" >
                                                    <option value="" selected disabled hidden >Select End Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="Monthly" class="mt-2 categories">
                                        <div class="row">
                                            <div class="col-12">
                                                <select name="monthly_year" id="monthly_year" class="form-control yearpicker" >
                                                    <option value="" selected disabled hidden >Select Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="Quarterly" class="mt-2 categories">
                                        <div class="row">
                                            <div class="col-12">
                                                <select name="quarterly_year" id="quarterly_year" class="form-control yearpicker" >
                                                    <option value="" selected disabled hidden >Select Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-success w-25">Generate</button>
                                </div>
                            </div>   
                        </form>
                    </div>     
                </div>
            </div>
        </div>
    </div> 
<script>
    $(document).ready(function () {
        $('#appointment-list').DataTable();

        function removeRequired(){
            $("#appointment_start_year").attr("required", false);
            $("#appointment_end_year").attr("required", false);
            $("#appointment_start_year").val("");
            $("#appointment_end_year").val("");
            $("#monthly_year").attr("required", false);
            $("#monthly_year").val("");
            $("#quarter_year").attr("required", false);
            $("#quarter_year").val("");

        }
            $("input:radio[name=\'category\']").change(function() {

                $(".categories").hide();
                removeRequired();

                if(this.value == 'Yearly' && this.checked){
                    $("#Yearly").show();
                    $("#appointment_start_year").attr("required", true);
                    $("#appointment_end_year").attr("required", true);
                }
                else if(this.value == 'Monthly' && this.checked){
                    $("#Monthly").show();
                    $("#monthly_year").attr("required", true);
                }
                else if(this.value == 'Quarterly' && this.checked){
                    $("#Quarterly").show();
                    $("#quarterly_year").attr("required", true);
                }
            
            });

            $("#appointment_form").submit(function( event ) {

                var startYear = $("#appointment_start_year").val(); 
                var endYear = $("#appointment_end_year").val();
    
                if(startYear !== null ||  endYear !== null){
                    if(startYear !== "" ||  endYear !== ""){
                        if(startYear < endYear){
                            return;
                        }
                        alert("End Year must be higher than Start Year");
                        event.preventDefault();
                    }
                }
            });

            let startYear = 2010;
            let endYear = new Date().getFullYear()-1;
            for (i = endYear; i > startYear; i--){
                $('.yearpicker').append($('<option />').val(i).html(i));
            }
    });
</script>
@endsection