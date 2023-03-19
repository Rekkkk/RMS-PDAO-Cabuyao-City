@extends('landingpage.navigation.navigation')

@section('content')
    
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col">
            <h3>Program Title : <b>{{ $selectedProgram->program_title }}</b></h3>
            <h5 style="font-size: 18px; "> <b> Disabilty Involve : </b> {{ $selectedProgram->disability_involve}}</h5>
            <h5 style="font-size: 18px; "><b>Program Type :  </b> {{ $selectedProgram->program_type }}</h5>
            <h5 style="font-size: 18px; "><b> Barangay Covered : </b>
            @if($selectedProgram->barangay->barangay_id == 1)
                All Barangays
            @else
                {{ $selectedProgram->barangay->barangay_name }}
            @endif
            </h4>
            <p> <small class="text-muted">By on {{date('F d, Y', strtotime($selectedProgram->created_at))}}</small></p>
        </div>
    </div>
    <div id="demo" style="border-style: solid; border-radius: 5px;" class="carousel slide" data-ride="carousel">
        <ul class="carousel-indicators">
            @foreach($selectedProgram->pictures as $pictures)
                @if($loop->index == 0)
                    <li data-target="#demo" data-slide-to="0" class="active"></li>
                @else
                <li data-target="#demo" data-slide-to="{{$loop->index}}"></li>
                @endif
            @endforeach
        </ul>
        <div class="carousel-inner">
            @foreach($selectedProgram->pictures as $pictures)
                @if($loop->index == 0)
                    <div class="carousel-item active">
                        <img src="{{ asset('images/'.$pictures->img_name) }}" class="img-corousel" width="100%" height= "450">
                    </div>
                @else
                    <div class="carousel-item">
                        <img src="{{ asset('images/'.$pictures->img_name) }}" class="img-corousel"width="100%" height= "450">
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
    <div class="row m-3">
        <div class="col-lg-12">
            <h3>Program Description :</h3>
            <p style="overflow: auto; font-size: 15px; height: 200px;">  {!! nl2br(e($selectedProgram->program_description))!!}</p>
        </div>
    </div>    
</div>
    <script>
        $(document).ready( function() {
            $(".img-corousel").click( function() {
                this.requestFullscreen();
            });
       });
    </script>
@endsection