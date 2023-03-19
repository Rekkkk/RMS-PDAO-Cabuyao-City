@extends('userpages.sidebar')

@section('content')
<button onclick="window.location='{{ route('view.beneficiaries', $program->program_id) }}';"class="btn btn-primary buttons mb-4">Back</button>
    <div class="container-fuild px-4">
        <div class="row">
            <div class="col">
                <h1><b>{{ $program->program_title}}</b></h1>
            </div>
           
        </div>
        <br>
        <table id="pwd-list" class="table table-hover" style="width:100%" >
            <thead>
                <tr>
                    
                    <th>PWD ID</th>
                    <th>Name</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody id="pwd_list" style="cursor: pointer">
                @foreach($beneficiaryList as $claimants)
                <tr>
                    <td>{{$claimants->pwd_number}}</td>
                    <td>{{$claimants->last_name. ", " . $claimants->first_name ." ".$claimants->middle_name}}</td>
                    <td>
                        @if($claimants->pivot->is_unclaim == 0)
                        <a href="{{ route('is.claim', ['pwd' => $claimants->pwd_id, 'program' => $program->program_id] ) }}" class="btn btn-success" style="width: 90px; margin-top: -5px; margin-bottom : -6px; font-size: 12px;">Claimed</a>
                        @else
                        <a href="{{ route('is.claim', ['pwd' => $claimants->pwd_id, 'program' => $program->program_id] ) }}" class="btn btn-danger" style="width: 90px; margin-top: -5px; margin-bottom : -6px; font-size: 12px;">Unclaimed</a>
                        @endif
                    </td>
                </tr>               
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="{{ asset('/js/jquery/dataTables.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#pwd-list').DataTable();
        });
    </script>
@endsection