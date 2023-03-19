@extends('landingpage.navigation.navigation')

@section('content')
    <div class="container-fuild px-4">
        <h1 class="ml-4 mt-4"><b>All PDAO Programs</b></h1>
        <div class="row">
            @foreach($programs as $program)
                <div class="col-lg-4"> 
                    <div class="program-item" onclick="window.location='{{ route('guest-view-programs', $program->program_id) }}';">
                        <h4><b>{{$program->program_title}}</b></h4>
                        <p> <small class="text-muted">By on {{date('F d, Y', strtotime($program->created_at))}}</small></p>
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
@endsection