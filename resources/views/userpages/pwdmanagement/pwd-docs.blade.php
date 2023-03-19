@extends('userpages.sidebar')

@section('content')
<a href="{{ route('view.pwd', $pwd->pwd_id) }}" class="btn btn-primary buttons mb-3">Back</a><br> 
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12">
            <h3>PWD Number :  <b>{{ $pwd->pwd_number }}</b></h3>
            <h3>PWD Name : <b>{{ $pwd->first_name . " " . $pwd->middle_name . " " .$pwd->last_name }} </b></h3>
        </div>
    </div><hr>
    @if($applicationDocs->count() != 0)
        <h3><b>Application Documents </b> <br> <p class="h6 mt-1">Date of Transaction : {{date('F d, Y', strtotime($applicationDocs->first()->created_at))  }}</p></h3>
        <label class="h5"><b>Medical Certificate</b></label>
        <div class="row">
            <div class="col-md-12">
                @foreach($applicationDocs as $picture)
                    @if($picture->docs_type == "Medical Certificate")
                        <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                    @endif
                @endforeach
            </div>
        </div>
        <label class="h5"><b>Voters Confirmation</b></label>
        <div class="row">
            <div class="col-md-12">
                @foreach($applicationDocs as $picture)
                    @if($picture->docs_type == "Voters Confirmation")
                        <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                    @endif
                @endforeach
            </div>
        </div>
        @if($applicationDocs->where('docs_type', 'Authorization')->count() != 0)
            <label class="h5"><b>Authorization Letter</b></label>
            <div class="row">
                <div class="col-md-12">
                    @foreach($applicationDocs as $picture)
                        @if($picture->docs_type == "Authorization")
                                <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endif
    @if($renewalDocs->count() != 0)
    <hr>
        <h3><b>Renewal Documents</b><br> <p class="h6 mt-1">Date of Transaction : {{date('F d, Y', strtotime($renewalDocs->first()->created_at))  }}</p></h3>
        <label class="h5"><b>Medical Certificate</b></label>
        <div class="row">
            <div class="col-md-12">
                @foreach($renewalDocs as $picture)
                    @if($picture->docs_type == "Medical Certificate")
                        <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                    @endif
                @endforeach
            </div>
        </div>
        @if($renewalDocs->where('docs_type', 'Authorization')->count() != 0)
            <label class="h5"><b>Authorization Letter</b></label>
            <div class="row">
                <div class="col-md-12">
                    @foreach($renewalDocs as $picture)
                        @if($picture->docs_type == "Authorization")
                            <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endif
    @if($lostIdDocs->count() != 0)
    <hr>
        <h3><b>Lost ID Documents</b> <br> <p class="h6 mt-1">Date of Transaction : {{date('F d, Y', strtotime($lostIdDocs->first()->created_at))  }}</p></h3>
        <label class="h5"><b>Affidavit Of Lost</b></label>
        <div class="row">
            <div class="col-md-12">
                @foreach($lostIdDocs as $picture)
                    @if($picture->docs_type == "Affidavit Of Lost")
                        <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                    @endif
                @endforeach
            </div>
        </div>
        @if($lostIdDocs->where('docs_type', 'Authorization')->count() != 0)
        <label class="h5"><b>Authorization Letter</b></label>
        <div class="row">
            <div class="col-md-12">
                @foreach($lostIdDocs as $picture)
                    @if($picture->docs_type == "Authorization")
                            <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                    @endif
                @endforeach
            </div>
        </div>
        @endif 
    @endif
    @if($cancellationDocs->count() != 0)
    <hr>
        <h3><b>Request Cancellation Documents</b><p class="h6 mt-1">Date of Transaction :{{date('F d, Y', strtotime($cancellationDocs->first()->created_at))  }}</p></h3>
        <label class="h5"><b>PWD ID</b></label>
        <div class="row">
            <div class="col-md-12">
                @foreach($cancellationDocs as $picture)
                    @if($picture->docs_type == "PWD ID")
                        <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                    @endif
                @endforeach
            </div>
        </div>
        @if($cancellationDocs->where('docs_type', 'Authorization')->count() != 0)
            <label class="h5"><b>Authorization Letter</b></label>
            <div class="row">
                <div class="col-md-12">
                    @foreach($cancellationDocs as $picture)
                        @if($picture->docs_type == "Authorization")
                            <a class="h6" href="{{ asset('/images/'.$picture->img_name) }}" target="_blank">{{$picture->img_name}}</a><br>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>  
@endsection