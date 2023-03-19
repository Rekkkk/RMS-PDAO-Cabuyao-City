@extends('userpages.sidebar')

@section('content')
<style>
    .img-corousel{
        cursor: pointer;
    }
</style>
    <button onclick="window.location='{{ route('program.management', 'A-Z') }} ';" class="btn btn-primary buttons mb-4">Back</button>
    <div class="container-fluid px-4">
        @include('sweetalert::alert')
        <div class="row">
            <div class="col-xl-12 d-flex justify-content-end">
                @if(Auth::user()->user_role == 2)
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" style="width: 155px;" data-toggle="dropdown">
                      Set Status
                    </button>
                    <div class="dropdown-menu text-center">
                        @if($program->programStatus->encoding_status == 0)
                            <a href="{{ route('set.encoding', $program) }}" class="dropdown-item text-info" ><b>Start Encode</b></a>
                        @else
                            <a href="{{ route('set.encoding', $program) }}" class="dropdown-item text-danger"><b>Close Encode</b></a>
                        @endif
                        @if($program->programStatus->is_done == 0)
                            <a class="dropdown-item text-success" href="{{ route('set.status', $program) }}"><b>Mark as Done</b></a>
                        @else
                            <a class="dropdown-item text-danger" href="{{ route('set.status', $program) }}"><b>Mark as Undone</b></a>
                        @endif
                    </div> 
                </div>
                @endif
                <div class="dropdown dropleft float-right ml-1">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                      More Options
                    </button>
                    <div class="dropdown-menu text-center">
                        <a href="" data-toggle="modal" data-target="#memo" class="dropdown-item " ><b>View Memo</b></a>
                        @if(Auth::user()->user_role == 2)
                        <a href="{{ route('email.pwd', $program ) }}" class="dropdown-item "><b> Email PWD's</b></a> 
                        <a href="{{ route('edit.program', $program) }}" class="dropdown-item "><b>Edit Program</b></a>
                        @endif    
                    </div> 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <h5 style="font-size: 27px; "><b>Program Title :</b> {{ $program->program_title }} </h5>
                <h5 style="font-size: 18px; "> <b> Disabilty Involve : </b> {{ $program->disability_involve}}</h5>
                <h5 style="font-size: 18px; "><b>Program Type :  </b> {{ $program->program_type }}</h5>
                <h5 style="font-size: 18px; "><b> Barangay Covered : </b>
                    @if($program->barangay->barangay_id == 1)
                        All Barangays
                    @else
                        {{ $program->barangay->barangay_name }}
                    @endif
                </h4>
                <h5 style="font-size: 18px; "> <b>Program Status :</b> 
                @if($program->programStatus->is_done == 1)
                    <span class=" text-success h5 mt-1">Done</span>
                @elseif($program->programStatus->encoding_status == 0)
                    <span class=" text-danger h5">Ongoing</span>
                @else
                <span class=" text-danger h5"><b>Encoding</b></span>
                @endif    
            </h5>
                <p class="text-muted">Created on {{date('F d, Y', strtotime($program->created_at))}}</p>
            </div>
            <div class="col-lg-3 mt-3 text-right">
                    <a href="{{ route('view.beneficiaries', $program->program_id) }}"  style="font-size: 16px">View List of Beneficaries</a>
            </div>
        </div>
        @if($program->pictures->first() != null)
            <div id="demo" style="border-style: solid; border-radius: 5px;" class="carousel slide" data-ride="carousel">            
                <ul class="carousel-indicators">
                    @foreach($program->pictures as $pictures)
                        @if($loop->index == 0)
                            <li data-target="#demo" data-slide-to="0" class="active"></li>
                        @else
                        <li data-target="#demo" data-slide-to="{{$loop->index}}"></li>
                        @endif
                    @endforeach
                </ul>
                <div class="carousel-inner">
                    @foreach($program->pictures as $pictures)
                        @if($loop->index == 0)
                        <div class="carousel-item active">
                            <img src="{{ asset('images/'.$pictures->img_name) }}" class="img-corousel" width="100%" height= "420">
                        </div>
                        @else
                        <div class="carousel-item">
                            <img src="{{ asset('images/'.$pictures->img_name) }}" class="img-corousel"width="100%" height= "420">
                        </div>
                        @endif
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#demo" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#demo" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>
            @endif
            <div class="row m-3">
                <div class="col-md-12">
                    <h3>Program Description :</h3>
                    <p style="overflow: auto; font-size: 15px; height: 200px;">  {!! nl2br(e($program->program_description))!!}</p>
                </div>
            </div>
            <div class="modal fade" id="memo">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Program Memorandum</h5>
                            <button type="button" class="close" data-dismiss="modal" >&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach($program->memorandum as $memorandum)
                                  
                                        <div class="col-md-4 thumb">
                                            <div class="img-wraps">  
                                                <img src="{{ asset('/images/'.$memorandum->img_name) }}" style="border-style: solid; border-radius: 5px;" width="140" height= "150">\
                                            </div>
                                        </div>
                               
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
    </div>

    <script>
        $(document).ready( function() {
            $("img").click( function() {
                this.requestFullscreen();
            });
       });
    </script>
@endsection