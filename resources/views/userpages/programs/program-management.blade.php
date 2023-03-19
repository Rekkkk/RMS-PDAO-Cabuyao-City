@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-lg-5">
                <h1><b>PDAO Programs</b></h1>
            </div>
            @if(Auth::user()->user_role == 2)
                <div class="col-lg-7 text-right">
                    <a href="{{ route('create.program') }}" class="btn btn-primary">Create Program</a>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                <table id="pwd-list" class="table table-hover" style="width:100%" >
                    <thead>
                        <tr>
                            <th>Program Title</th>
                            <th>Program Type</th>
                            <th>Disability Involve</th>
                            <th>Barangay</th>
                            <th>Date Created</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="myTable" style="cursor: pointer">
                        @foreach($programs as $program)
                        <tr onclick="window.location='{{ route('view.program', $program->program_id) }}';">
                            <td>{{ $program->program_title }}</td>
                            <td>{{ $program->program_type }}</td>
                            <td>{{ $program->disability_involve }}</td>
                            @if($program->barangay->barangay_id == 1)
                                <td>All Barangays</td>
                            @else
                                <td>{{ $program->barangay->barangay_name }}</td>
                            @endif
                            <td>{{date('F d, Y', strtotime($program->created_at))}}</td>
                            <td> 
                                @if($program->programStatus->is_done == 1)
                                    <span class=" mt-1 text-success"><b>Done</b></span>
                                @elseif($program->programStatus->encoding_status == 0)
                                    <span class=" mt-1 text-danger"><b>Ongoing</b></span>
                                @else
                                    <span class=" mt-1 text-danger"><b>Encoding</b></span>
                                @endif    
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>  
    <script>
           $(document).ready(function(){
                $('#pwd-list').DataTable();
           });
    </script>
@endsection