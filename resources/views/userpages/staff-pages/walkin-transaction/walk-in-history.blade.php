@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
<a class="btn btn-primary buttons mb-2" href="{{ route('pwd.management') }}">Back</a>
    <div class="container-fluid px-4">
        <div class="row mb-3">
            <div class="col-lg-12">
                <h1><b>WALK-IN'S HISTORY</b></h1>
            </div>
        </div>
        <table id="pwd-list" class="table" style="width:100%" >
            <thead>
                <tr>
                    <th>PWD No.</th>
                    <th>Barangay</th>
                    <th>Transaction</th>
                    <th>Name</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="myTable" style="cursor: pointer">
                @foreach($walkIn as $walkIns)       
                <tr>
                    <td>{{$walkIns->pwd->pwd_number}}</td>
                    <td>{{$walkIns->barangay->barangay_name}}</td>
                    <td>{{$walkIns->transaction}}</td>
                    <td>{{$walkIns->pwd->first_name . " " .$walkIns->pwd->last_name." " .$walkIns->pwd->middle_name}}</td>
                    <td>{{date('F j, Y - g:i:s a', strtotime($walkIns->created_at))}}</td>
                    {{-- <td>{{$pwds->last_name}}</td>
                    <td>{{$pwds->middle_name}}</td>
                    @if(Auth::user()->user_role == 2)
                    <td>{{$pwds->barangay->barangay_name}}</td>
                    @endif
               
                    @if ($pwds->pwd_status->id_expiration > date("Y-m-d"))
                    <td class="text-success">Active</td>
                    @else
                    <td class="text-danger">Inactive</td>
                    @endif --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <div class="modal fade" id="appointment-reports">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <div class="modal-body">
                    <div class="w-75 m-auto">
                        <form action="{{ route('appointment.report.generate') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <label class="title-detail">Month :</label>
                                    <input type="month" class="form-control" name="target_month" >
                                </div>
                            </div>              
                    </div>     
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>  
    <script>
        $(document).ready(function () {
            $('#pwd-list').DataTable();
        });
    </script>
@endsection