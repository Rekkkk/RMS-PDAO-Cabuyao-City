@extends('userpages.sidebar')

@section('content')
<button onclick="window.location='{{ route('view.program', $program) }}';" class="btn btn-primary buttons mb-4">Back</button>

    <div class="container-fluid px-4">
        @include('sweetalert::alert')
        <div class="row mb-2">
            <div class="col-lg-6">
                <h1><b>List of Beneficaries</b></h1> 
            </div>
            <div class="col-lg-6 text-right">
                @if($program->programStatus->encoding_status == 0 && $program->programStatus->is_done == 0)
                    <a class="btn btn-primary" href="{{ route('initial.report.attendee', $program) }}">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        Print Beneficiary List
                    </a>
                @endif
            </div>
        </div>
       <table id="pwd-list" class="table table-hover" style="width:100%" >  
            <thead>
                <tr>
                    <th>PWD No.</th>
                    <th>Name</th>
                    <th>Barangay</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody id="myTable" style="cursor: pointer">
                @foreach($beneficiaryList as $pwd)  
                <tr>
                    <td>{{$pwd->pwd_number}}</td>
                    <td>{{$pwd->last_name .', ' . $pwd->first_name . ' ' .$pwd->middle_name}}</td>
                    <td>{{$pwd->barangay->barangay_name}}</td>
                    @if($program->programStatus->is_done == 1)
                        @if($pwd->pivot->is_unclaim == 0)
                        <td class="text-success">Attended</td>
                        @else
                        <td class="text-danger">Not Attended</td>
                        @endif
                    @else    
                        <td class="text-secondary">Unknown</td>
                    @endif
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function () {
            $('#pwd-list').DataTable();
        });
    </script>
@endsection