@extends('userpages.sidebar')

@section('content')
<button onclick="window.location='{{ route('view.program', $program) }}';"class="btn btn-primary buttons mb-4">Back</button>

    <div class="container-fluid px-4">
        @include('sweetalert::alert')

        <div class="row">
            <div class="col-lg-7">
                <h2><b>List of Beneficaries </b><b>
                @if(Auth::user()->user_role == 1)
                (Brgy. {{Auth::user()->barangay->barangay_name}})
                @endif
            </b>
            </h2>
        </div>
        <div class="col-lg-5 d-flex justify-content-end mb-3 ">
            @if($program->programStatus->is_done == 1)
                <div class="dropdown dropleft float-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" style="width: 160px;" data-toggle="dropdown">
                        Generate report
                    </button>
                    <div class="dropdown-menu text-center">
                        <a class="dropdown-item" href="{{ route('print.unclaim.list', $program) }}">Participation List</a>
                        <a class="dropdown-item" href="{{ route('program.report.summary', $program) }}">Summary Report</a>
                    </div> 
                </div>
            @elseif($program->programStatus->encoding_status == 0 && $program->programStatus->is_done == 0)
                <div class="dropdown dropleft float-right">
                    <button type="button" class="btn btn-primary dropdown-toggle buttons" data-toggle="dropdown">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        Generate
                    </button>
                    <div class="dropdown-menu text-center">
                        <a class="dropdown-item" href="{{ route('initial.report.attendee', $program) }}">Expected Attendees</a>
                        <a class="dropdown-item" href="{{ route('print.beneficiaries', $program) }}">Signatory List</a>
                    </div> 
                </div>
            @else
            <a href="{{ route('beneficiaries.details', $program) }}" class="btn btn-info buttons">Encode</a>
            @endif
           
        </div>
        </div>
        @if($program->programStatus->encoding_status == 0 && $program->programStatus->is_done == 0 )
        <div class="row">
            <div class="col-lg-12 text-right mb-3">
               
            </div>
        </div>
        @endif
            
       <table id="pwd-list" class="table" style="width:100%" >
            <thead>
                <tr>
                    <th>PWD No.</th>
                    <th>Name</th>
                    <th>Status</th>
                
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach($beneficiaryList as $pwd)  
                <tr>
                    <td>{{$pwd->pwd_number}}</td>
                    <td>{{$pwd->last_name .', ' . $pwd->first_name . ' ' .$pwd->middle_name}}</td>
                    @if($program->programStatus->is_done == 1)
                        @if($pwd->pivot->is_unclaim == 0)
                            <td class="text-success">
                                Attended
                        @else
                            <td class="text-danger">
                                Not Attended
                        @endif
                        
                    @else
                    <td class="text-secondary">
                        Unknown
                   
                    @endif
                </td>
                 
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