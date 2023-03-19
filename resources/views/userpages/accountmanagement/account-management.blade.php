@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-lg-8">
                @if(Auth::user()->user_role == 3)
                    <h1><b>OIC Account Manager</b></h1>                
                @else
                <h1><b>Account Management</b></h1>                
                @endif
             
            </div>
            <div class="col-lg-4 text-right">
                @if(Auth::user()->user_role == 3)
                <a href="{{ route('create.account.oic') }}" class="btn btn-primary">Add OIC Account</a><br><br>
                @else
                <a href="{{ route('create.account') }}" class="btn btn-primary buttons">Add Account</a><br><br>
                @endif
            </div>
        </div>
        <table id="accounts-list" class="table table-hover table-bordered" style="width:100%" >
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Last name</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    @if(Auth::user()->user_role == 3)
                    <th>Position</th>
                    @else
                    <th>Handle Barangay or Position</th>
                    @endif
                   
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="myTable" style="cursor: pointer">
                @foreach($users as $user)       
                @if(Auth::user()->user_role == 3)
                <tr onclick="window.location='{{ route('view.oic.account', $user->user_id) }}';">  
                @else
                <tr onclick="window.location='{{ route('view.account', $user->user_id) }}';">  
                @endif
                     <td>USR-0{{ $user->user_id }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->middle_name }}</td>
                        @if(Auth::user()->user_role == 3)
                            <td>Officer In Charge
                        @else
                            <td>{{ $user->barangay->barangay_name }}
                        @endif
                        </td>   
                        @if($user->userStatus->is_disable == 1)
                            <td class="text-danger">Inactive</td>  
                        @elseif($user->userStatus->is_suspend == 1 && $user->userStatus->suspend_start < date('Y-m-d'))
                            <td class="text-danger">Inactive Temporary</td>  
                        @else
                            <td class="text-success">Active</td>  
                        @endif
                                                                          
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function () {
            $('#accounts-list').DataTable();
        });
    </script>
@endsection