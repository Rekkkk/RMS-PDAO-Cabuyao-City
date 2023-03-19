@extends('landingpage.navigation.navigation')

@section('content')
    <div class="container mt-3">
        <div class="row mb-5 ">
            <div class="col-lg-12">
                <img src="{{ asset('img/sample.png') }}" style="margin-left: 2%" class="mb-5 mt-2">
                <div class="text-center">
                    <h2 style="font-size: 50px"><b>PDAO Cabuyao City</b></h2>
                    <p class="h5">Register and be a member of PWD Community in Cabuyao City</p><br>
                    <a href="{{ route('appointment.new-applicant') }}" style="width: 200px" data-toggle="tooltip" title="Apply Now" class="btn btn-success">Apply Now</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1><b>PDAO Cabuyao Programs</b></h1><br>
            </div>
        </div><br>
            <div class="row">
                <p style="margin: auto; width: 600px; max-width: 80%; text-align: center; font-size: 16px;">Stay up-to-date with the latest programs for Person With Disability from Cabuyao Government.</p>
            </div><br><br>
            <div class="row mb-5">
                @if($programs->count() == 0)
                    <h2 style="text-align: center; margin: auto; font-size: 40px;">No Programs Posted</h2><br>
                @endif
                @foreach($programs->take(3) as $program)
                    <div class="col-lg-4 mt-2"> 
                        <div class="program-item" onclick="window.location='{{ route('guest-view-programs', $program->program_id) }}';">
                            <h4><b>{{$program->program_title}}</b></h4>                        <p> <small class="text-muted">By on {{date('F d, Y', strtotime($program->created_at))}}</small></p>
                            <p>{{$program->program_description}}</p>
                            @if($program->pictures->first() !== null)
                            <img src="{{ asset('images/'.$program->pictures->first()->img_name) }}" width="100%" height="200">
                            @else
                            <img src="{{ asset('images/no-image.jpg') }}" width="100%" height="200">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($programs->count() != 0)
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('programs') }}"  class="btn btn-success">More Programs</a>
                    </div>
                </div>
            @endif
     
    </div>
@endsection