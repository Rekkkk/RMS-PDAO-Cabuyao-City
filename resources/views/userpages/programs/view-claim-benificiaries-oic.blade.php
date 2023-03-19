@extends('userpages.sidebar')

@section('content')
<button onclick="window.location='{{ route('view.program', $program) }}';" class="btn btn-primary buttons mb-4">Back</button>

    <div class="container-fluid px-4">
        @include('sweetalert::alert')

        <div class="row mb-2">
            <div class="col-lg-6">
                <h1><b>List of Beneficaries</b></h1> 
            </div>
            <div class="col-lg-6 d-flex justify-content-end mb-3">
                @if($program->programStatus->encoding_status == 0 && $program->programStatus->is_done == 0)
                <div class="dropdown dropleft float-right">
                    <button type="button" class="btn btn-primary dropdown-toggle buttons" data-toggle="dropdown">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        Generate
                    </button>
                    <div class="dropdown-menu text-center">
                        <a class="dropdown-item" href="" data-toggle="modal" data-target="#barangay-list-report" >Beneficiary List</a>
                    </div> 
                </div>
                @elseif($program->programStatus->is_done == 1)
                    <div class="dropdown dropleft float-right">
                        <button type="button" class="btn btn-primary dropdown-toggle" style="width: 160px;" data-toggle="dropdown">
                            <i class="fa fa-print" aria-hidden="true"></i>
                            Generate
                        </button>
                        <div class="dropdown-menu text-center">
                            <a class="dropdown-item" data-toggle="modal" href="" data-target="#summary-report" >Summary Report</a>
                        </div> 
                    </div>
                    <div class="dropdown ml-1">
                        <button type="button" class="btn btn-primary dropdown-toggle" style="width: 170px;" data-toggle="dropdown">
                            More Options
                        </button>
                        <div class="dropdown-menu text-center">
                            @if($program->programStatus->is_done == 0)
                                <a class="dropdown-item" href="" data-toggle="modal" data-target="#upload-signatory">Upload Signatory</a>
                            @endif
                            <a class="dropdown-item" href="" data-toggle="modal" style="width: 170px;" data-target="#view-signatory">View Signatory</a>
                        </div> 
                    </div>
                
                @else
                    <h5 class="text-info">Encoding Data ...</h5>
                @endif
               
            </div>
        </div>
       <table id="pwd-list" class="table" style="width:100%" >  
            <thead>
                <tr>
                    <th>PWD No.</th>
                    <th>Name</th>
                    <th>Barangay</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="myTable" style="cursor: pointer">
                @foreach($beneficiaryList as $pwd)  
                <tr>
                    <td>{{$pwd->pwd_number}}</td>
                    <td>{{$pwd->last_name .', ' . $pwd->first_name . ' ' .$pwd->middle_name}}</td>
                    <td>{{$pwd->barangay->barangay_name}}</td>
                  
                        @if($program->programStatus->is_done == 1)
                            @if($program->program_type == "Cash Gifts or Grocery Packs")
                                @if($pwd->pivot->is_unclaim == 0)
                                    <td class="text-success">Claimed</td>
                                @else
                                    <td class="text-danger">Unclaimed</td>
                                @endif
                                @else
                                    @if($pwd->pivot->is_unclaim == 0)
                                        <td class="text-success">Attended</td>
                                    @else
                                        <td class="text-danger">Not Attended</td>
                                    @endif
                            @endif
                        @else    
                            <td class="text-secondary">Unknown</td>
                        @endif
                </tr>   
                @endforeach
            </tbody>
        </table>
      
    </div>
    <div class="modal" id="summary-report">
        <div class="modal-dialog ">
            <div class="modal-content"  >
                <div class="modal-header">
                    <h4 class="modal-title">Select Barangay</h4>
                    <button type="button" id="close-button-modal" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <form action="{{ route('program.report.summary.oic', $program) }}" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label class="title-detail">Select Barangay : </label>
                            <select name="barangay_id" class="form-control" required>
                                <option selected disabled value="" >Please Select Barangay</option>
                                @if($program->barangay->barangay_id == 1)
                                    <option value="20" {{ old('barangay_id') == 'All Barangays' ? 'selected' : '' }}>All Barangays</option>
                                    @foreach($barangays as $key => $barangay)
                                        @if($key > 0)
                                        <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_name ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                                        @endif
                                    @endforeach
                                @else
                                <option value="{{ $program->barangay->barangay_id }}" >{{ $program->barangay->barangay_name }}</option>
                                @endif
                            </select>
                        @if ($errors->has('barangay_id'))
                            <span class="text-danger">{{ $errors->first('barangay_id') }}</span>
                        @endif
                        </div>
                    </div>                      
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Generate  </button>
                </div>
            </form>
            </div>
        </div>
    </div>
    
    <div class="modal" id="barangay-list-report">
        <div class="modal-dialog ">
            <div class="modal-content"  >
                <div class="modal-header">
                    <h4 class="modal-title">Select Barangay</h4>
                    <button type="button" id="close-button-modal" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <form action="{{ route('initial.report.beneficiaries.oic', $program) }}" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label class="title-detail">Select Barangay : </label>
                            <select name="barangay_id" class="form-control" required>
                                <option selected disabled value="" >Please Select Barangay</option>
                                @if($program->barangay->barangay_id == 1)
                                    <option value="20" {{ old('barangay_id') == 'All Barangays' ? 'selected' : '' }}>All Barangays</option>
                                    @foreach($barangays as $key => $barangay)
                                        @if($key > 0)
                                        <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_name ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                                        @endif
                                    @endforeach
                                @else
                                <option value="{{ $program->barangay->barangay_id }}" >{{ $program->barangay->barangay_name }}</option>
                                @endif
                            </select>
                        @if ($errors->has('barangay_id'))
                            <span class="text-danger">{{ $errors->first('barangay_id') }}</span>
                        @endif
                        </div>
                    </div><br>
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

    <div class="modal" id="view-signatory">
        <div class="modal-dialog ">
            <div class="modal-content"  >
                <div class="modal-header">
                    <h4 class="modal-title">View Signatory</h4>
                    <button type="button" id="close-button-modal" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <div class="modal-body">

                    <h5 class="text-left"><b>Signatory List Per Barangay</b></h5>
                    @foreach($barangays as $key => $barangay)
                    @if($key > 0)
                        <label class="title-detail">{{ $barangay->barangay_name }}</label><br>
                        
                            @if($signatory->where('barangay_id', $barangay->barangay_id)->count() == 0)
                                <label class="ml-4">No Signatory List Uploaded</label>
                            @else
                                @foreach($signatory as $file)
                                    @if($file->barangay_id == $barangay->barangay_id)
                                        <a href="{{ asset('signatory/'.$file->file_name) }}" class="ml-4" target="_blank">{{ $file->file_name }}</a>
                                    @endif
                                @endforeach
                            @endif
                        <br>
                    @endif
                    @endforeach
                    
                    
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