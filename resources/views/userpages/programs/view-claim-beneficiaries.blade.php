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
                        <i class="fa fa-print" aria-hidden="true"></i>
                        Generate
                    </button>
                    <div class="dropdown-menu text-center">
                        @if($program->program_type == "Cash Gifts or Grocery Packs")
                        <a class="dropdown-item" href="{{ route('print.unclaim.list', $program) }}">
                            Unclaimed List
                        </a>
                        @endif
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
                        <a class="dropdown-item" href="{{ route('print.beneficiaries', $program) }}">Signatory List</a>
                        <a class="dropdown-item" href="{{ route('initial.report.beneficiaries', $program) }}">Beneficiary List</a>
                    </div> 
                </div>
            @else
            <a href="{{ route('beneficiaries.details', $program) }}" class="btn btn-primary buttons">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                Encode
            </a>
            @endif
            @if($program->programStatus->encoding_status == 1 || $program->programStatus->is_done == 1)
                <div class="dropdown dropleft float-right ml-1">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        More Options
                    </button>
                    <div class="dropdown-menu text-center">
                        @if($program->programStatus->is_done == 0)
                            <a class="dropdown-item" href="" data-toggle="modal" data-target="#upload-signatory">Upload Signatory</a>
                        @endif
                        <a class="dropdown-item" href="" data-toggle="modal" data-target="#view-signatory">View Signatory</a>

                    </div> 
                </div>
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
                    @if($program->programStatus->is_done == 1)
                    <th>Status</th>
                    @endif
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach($beneficiaryList as $pwd)  
                    <tr>
                        <td>{{$pwd->pwd_number}}</td>
                        <td>{{$pwd->last_name .', ' . $pwd->first_name . ' ' .$pwd->middle_name}}</td>                                      
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
                           
                        @endif
                    </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal" id="upload-signatory">
        <div class="modal-dialog ">
            <div class="modal-content"  >
                <div class="modal-header">
                    <h4 class="modal-title">Upload Beneficiaries Signatory</h4>
                    <button type="button" id="close-button-modal" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('upload.signatory', ['barangay' => Auth::user()->barangay_id, 'program' => $program]) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <span class="text-danger">*You can only upload scan copy in PDF form </span><br>
                        <label class="title-detail mt-1">Select File : </label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input " id="customFile" name="signatory[]" accept="application/pdf" required>
                            <label class="custom-file-label" for="customFile">Scan Document (Pdf format)</label>
                        </div><br>
                        <div class="row mt-3">
                            <div class="col text-right">
                                <input type="submit" class="btn btn-success w-25" value="Upload">
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

                    <h5 class="text-left"><b>Signatory List</b></h5>
                    @foreach($signatory as $file)
                    <a href="{{ asset('signatory/'.$file->file_name) }}" class="mt-5 h6" target="_blank">{{ $file->file_name }}</a>
                    @endforeach
                    
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#pwd-list').DataTable();
            $(".custom-file-input").on("change", function() {
                var files = Array.from(this.files)
                var fileName = files.map(f =>{return f.name}).join(", ")
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });
    </script>
@endsection