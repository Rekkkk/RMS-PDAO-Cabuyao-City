@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
<style>
    .ui-datepicker-current { display: none; }
    .shadow {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        border-radius: 8px; 
    }
</style>
<a href="{{ route('appointment.page') }}"  class="btn btn-primary buttons m-1 mb-3">Back</a>
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-lg">
            <h1><b>Manage Appointment Dates</b></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="row mb-3">
                <div class="col-lg-8">
                    <h5><b>Appointment Limit Per Day</b></h5>
                </div>
                <div class="col-lg-4 text-right">
                    <button  data-toggle="modal" data-target="#appointment-setting" id="set-limit" class="btn btn-primary buttons ">Setup Limit</button>
                </div>
            </div>
            <div class="row" id="limits">
                <div class="col-lg-12 p-4">
                    <table id="limit" class="table table-hover" style="width:100%" >
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Transaction per day</th>
                                <th>Actions</th>
                          
                            </tr>
                        </thead>
                        <tbody id="myTable" style="cursor: pointer">
                            @foreach($appointmentLimit as $limit)       
                            <tr>
                                <td style="font-size: 15px;">{{date('F Y', strtotime($limit->appointment_month))}}</td>
                                <td style="font-size: 15px;">{{$limit->limits}}</td>
                                <td>
        
                                    <a class="btn btn-info " style="height: 27px; font-size:10px" 
                                        data-month="{{$limit->appointment_month}}" 
                                        data-limits="{{$limit->limits}}" 
                                        data-toggle="modal" 
                                        data-target="#edit">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        Edit
                                    </a>
                                </a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        
            </div>
        </div>
        <div class="col-xl-6">
            <div class="row">
                <div class="col-lg-8 mb-3">
                    <h4><b>Disable Appointment Dates</b></h4>
                </div>
                <div class="col-lg-4 text-right">
                    <button  data-toggle="modal" data-target="#appointment-disable-date" class="btn btn-primary buttons"> Disable Date</button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 p-4">
                    <table id="disable-date" class="table table-hover " style="width:100%" >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                            
                            </tr>
                        </thead>
                        <tbody id="myTable" style="cursor: pointer">
                            @foreach($disableDate as $disableDates)       
                            <tr>
                                <td>{{date('F d, Y', strtotime($disableDates->date))}}</td>
                                <td><a href="{{ route('remove.disable.date', $disableDates->date) }}" class="btn btn-danger" style="height: 27px; font-size: 10px"><i class="fa fa-trash"></i>
                                <span>Delete</span></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="appointment-disable-date">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Disable appointment date </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('disable.date') }}" method="POST">
                                @csrf
                                <label class="title-detail">Date:</label>
                                <input type="text" name="date" class="form-control" style="cursor: pointer" id="datepicker-disable-date" placeholder="Click to choose date" readonly="false">
                                @if ($errors->has('date'))
                                    <span class="text-danger">{{ $errors->first('date') }}</span>
                                @endif
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success buttons">Save  </button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
    <div class="modal fade" id="appointment-setting">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Limit Appointment </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('limit.appointment') }}" class="px-4" method="POST">
                        @csrf
                        <label class="title-detail">Appointment Month :</label>
                        <input type="text" name="appointment_month" class="form-control monthPicker" id="datepicker" placeholder="Click to choose date" readonly="false" required>
                        @if ($errors->has('appointment_month'))
                        <span class="text-danger">{{ $errors->first('appointment_month') }}</span>
                        @endif<br>
                        <label class="title-detail">No. of Transactions :</label>
                        <input type="number" name="limits" class="form-control" id="" required>
                        @if ($errors->has('limits'))
                        <span class="text-danger">{{ $errors->first('limits') }}</span>
                        @endif
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success buttons">Save</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Limit appointment Edit</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('edit.limit.appointment') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="date" id="month">
                    <div class="row">
                        <div class="col lg-12 d-flex">
                            <label class="title-detail" >Month : </label>
                            <p  id="month" class="detail-value" ></p><br><br>
                        </div>
                    </div>
                    
                    <label class="title-detail">Limits : </label>
		        	<input type="number" class="form-control" name="limit" id="limit" required>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
          </div>
        </div>
      </div>
</div>

<script src="{{ asset('/js/jquery/dataTables.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#disable-date').DataTable();
        $('#limit').DataTable();

    $('#edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) 
        var month = button.data('month') 
        var limit = button.data('limits') 
        var modal = $(this)
        
        let now = new Date(month).toLocaleDateString('en-us', {month:"long", year:"numeric"});

        modal.find('.modal-body #month').attr('value', month);
        modal.find('.modal-body #month').append(now);
        modal.find('.modal-body #limit').val(limit);
      
    });



    });
</script>


@endsection