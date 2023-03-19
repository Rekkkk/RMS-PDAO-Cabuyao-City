@extends('userpages.sidebar')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <h1><b>Activity Logs</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 p-4">
                <table id="log" class="table-bordered"  >
                    <thead>
                        <tr>
                            <td style="display: none">LogID</td>
                            <th class="w-25">Date</th>
                            <th>User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody  style="cursor: pointer">
                        @foreach($activityLogs as $activityLog)       
                        <tr>
                            <td style="display: none">{{$activityLog->log_id}}</td>
                            <td class="w-25">{{date('F d, Y h:i:s A', strtotime($activityLog->created_at)) }}</td>
                            <td>{{ $activityLog->user->first_name . " " . $activityLog->user->last_name }}</td>
                            <td>{{ $activityLog->eventdetails }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function(){
        $('#log').DataTable({
            order: [[0, 'desc']],
        });
    });
</script>

@endsection



