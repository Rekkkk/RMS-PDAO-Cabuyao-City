@extends('userpages.sidebar')

@section('content')
<style>
    .thumb{
    margin-bottom: 30px;
    }
    .page-top{
        margin-top:25px;
    }
    img.zoom {
        border-style: groove;
        cursor: pointer;
        width: 100%;
        height: 230px;
        border-radius:5px;
        object-fit:cover;
        -webkit-transition: all .3s ease-in-out;
        -moz-transition: all .3s ease-in-out;
        -o-transition: all .3s ease-in-out;
        -ms-transition: all .3s ease-in-out;
    }
    .transition {
        -webkit-transform: scale(1.2); 
        -moz-transform: scale(1.2);
        -o-transform: scale(1.2);
        transform: scale(1.2);
    }
    .img-wraps {
    position: relative;
    display: inline-block;
    
    font-size: 0;
    }
    .img-wraps .closes {
        position: absolute;
        top: 5px;
        right: 8px;
        z-index: 100;
        background-color: #FFF;
        padding: 4px 3px;
        
        color: #000;
        font-weight: bold;
        cursor: pointer;
        
        text-align: center;
        font-size: 22px;
        line-height: 10px;
        border-radius: 50%;
        border:1px solid red;
    }
    .img-wraps:hover .closes {
        opacity: 1;
    }
</style>
    @include('sweetalert::alert')
    <button onclick="window.location='{{ route('view.program', $program->program_id) }}';" class="btn btn-primary buttons mb-4">Back</button>
    <div class="container-fluid px-4" >
        <form action="{{ route('edit.program.save', $program) }}" enctype="multipart/form-data" method="POST">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="mb-4"><b>Edit PWD Program</b></h1>
                </div>
                <div class="col-lg-4">
                    <div class="col-lg">
                        <label class="title-detail">Upload Program Memo :</label>
                        <span class="text-danger">* Required</span>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input " id="customFile" name="memo[]" accept="image/*" multiple>
                            <label class="custom-file-label" for="customFile">Scan Document (any picture format)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-8">
                    <label class="title-detail">Program Title :  </label>
                    <span class="text-danger">* Required</span>
                    <input name="program_title" class="form-control" type="text" placeholder="Program Title" value="{{ $program->program_title }}" required>
                    @if ($errors->has('program_title'))
                        <span class="text-danger">{{ $errors->first('program_title') }}</span>
                    @endif
                </div>
                <div class="col-lg-4">
                    <label class="title-detail">Program Type</label>
                    <span class="text-danger">* Required</span>
                    <select name="program_type" id="program_type" class="custom-select required-field" required >
                        <option selected disabled value="">Select Program Type</option>
                        <option value="Education and Training"  class="program_type" {{ old('program_type') == 'Education and Training' ? 'selected' : '' }}>Education and Training</option>
                        <option value="Health and Medical Treatment"  class="program_type" {{ old('program_type') == 'Health and Medical Treatment' ? 'selected' : '' }}>Health and Medical Treatment</option>
                        <option value="Employment and Work"  class="program_type" {{ old('program_type') == 'Employment and Work' ? 'selected' : '' }}>Employment and Work</option>
                        <option value="Cash Gifts or Grocery Packs" class="program_type" {{ old('program_type') == 'Cash Gifts or Grocery Packs' ? 'selected' : '' }}>Cash Gifts or Grocery Packs</option>
                    </select>
                    @if ($errors->has('program_type'))
                        <span class="text-danger">{{ $errors->first('program_type') }}</span>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label class="title-detail">Barangay</label>
                    <span class="text-danger">* Required</span>
                    <select name="barangay_id" class="custom-select required-field" required >
                        <option selected disabled value="">Select Barangay</option>
                        <option value="1" class="barangay_id">All Barangays</option>
                        <option value="2" class="barangay_id">Baclaran</option>
                        <option value="3" class="barangay_id">Banay-Banay</option>
                        <option value="4" class="barangay_id">Banlic</option>
                        <option value="5" class="barangay_id">Bigaa</option>
                        <option value="6" class="barangay_id">Butong</option>
                        <option value="7" class="barangay_id">Casile</option>
                        <option value="8" class="barangay_id">Diezmo</option>
                        <option value="9" class="barangay_id">Gulod</option>
                        <option value="10" class="barangay_id">Mamatid</option>
                        <option value="11" class="barangay_id">Marinig</option>
                        <option value="12" class="barangay_id">Niugan</option>
                        <option value="13" class="barangay_id">Pittland</option>
                        <option value="14" class="barangay_id">Pulo</option>
                        <option value="15" class="barangay_id">Sala</option>
                        <option value="16" class="barangay_id">San Isidro</option>
                        <option value="17" class="barangay_id">Barangay I Poblacion</option>
                        <option value="18" class="barangay_id">Barangay II Poblacion</option>
                        <option value="19" class="barangay_id">Barangay III Poblacion</option>
                    </select>
                    @if ($errors->has('barangay_id'))
                        <span class="text-danger">{{ $errors->first('barangay_id') }}</span>
                    @endif
                </div>
                <div class="col-lg-4">
                    <label class="title-detail">Involved Disability Type </label>
                    <span class="text-danger">* Required</span>
                    <select name="disability_involve" class="custom-select required-field" required>
                        <option selected disabled value="">Select disability type</option>
                        <option value="All Disabilities" class="disability_involve">All Disabilities</option>
                        <option value="Deaf/Hard of hearing" class="disability_involve">Deaf/Hard of hearing</option>
                        <option value="Intellecttual Disability" class="disability_involve">Intellecttual Disability</option>
                        <option value="Learning Disability imparement" class="disability_involve">Learning Disability imparement</option>
                        <option value="Mental Disability" class="disability_involve">Mental Disability</option>
                        <option value="Physical Disability" class="disability_involve">Physical Disability</option>
                        <option value="Psychosocial Disability" class="disability_involve">Psychosocial Disability</option>
                        <option value="Speech and Language" class="disability_involve">Speech and Language</option>
                        <option value="Visual Disablity" class="disability_involve">Visual Disablity</option>
                    </select>
                    @if ($errors->has('disability_involve'))
                        <span class="text-danger">{{  $errors>first('disability_involve') }}</span>        
                    @endif
                </div>
                <div class="col-lg">
                    <label class="title-detail">Upload Image :</label><br>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input " id="customFile" name="images[]" accept="image/*" value="{{ old('images[]') }}" multiple>
                        <label class="custom-file-label" for="customFile">Select Pictures</label>
                    </div>
                </div>
            </div><h5>Program Images</h5>

            @if($program->pictures->count() != 0)
            <div class="row"> 
                @foreach($program->pictures as $picture)
                <div class="col-xl-2 col-md-4 col-xs-6 thumb">
                    <div class="img-wraps">                            
                        <span class="closes" onclick="window.location='{{ route('delete.image.program', $picture) }}';" title="Delete">×</span>

                    <img src="{{ asset('images/'.$picture->img_name) }}" style="border-style: solid; border-radius: 5px;"  width="150" height= "150">
                    </div>
                    
                </div>
                @endforeach
            </div>
            @else
            <h6><b>No Images Uploaded !</b></h6>

            @endif
            
            <h5>Program Memorandum</h5>
            @if($program->memorandum->count() != 0)
            <div class="row">
                @foreach($program->memorandum as $memorandum)
                <div class="col-xl-2 col-md-4 col-xs-6 thumb">
                    <div class="img-wraps">                            
                        <span class="closes" onclick="window.location='{{ route('delete.memo.program', $memorandum) }}';" title="Delete">×</span>
                    <img src="{{ asset('images/'.$memorandum->img_name) }}" style="border-style: solid; border-radius: 5px;"  width="150" height= "150">
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <h6><b>No Memorandum Uploaded !</b></h6>
            @endif
            
            <div class="row">
                <div class="col-lg-12">
                    <label class="title-detail">Program Description :</label>
                    <textarea name="program_description" class="form-control"  cols="10" rows="7">{{ $program->program_description }}</textarea>
                    @if ($errors->has('program_description'))
                    <span class="text-danger">{{ $errors->first('program_description') }}</span>
                    @endif
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-success buttons">Save Changes</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            
            $(".custom-file-input").on("change", function() {
                var files = Array.from(this.files)
                var fileName = files.map(f =>{return f.name}).join(", ")
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
            var program_type = $('.program_type');
            for(let i = 0; i <= program_type.length; i++){    
                let program_typeIndex = String(program_type[i].innerHTML);
                if(program_typeIndex == {!! json_encode($program->program_type) !!}){
                    // if(program_typeIndex == "Cash Gifts"){
                    //     document.getElementById('cash').disabled = false;
                    // }
                    $('.program_type').eq(i).attr('selected', '');
                    break;
                }
            }
            var barangay_id = $('.barangay_id');
            for(let i = 0; i <= barangay_id.length; i++){    
                let barangay_idIndex = String(barangay_id[i].innerHTML);
                if(barangay_id[i].getAttribute('value') == {!! json_encode($program->barangay->barangay_id) !!}){
                    $('.barangay_id').eq(i).attr('selected', '');
                    break; 
                }
            }
            var disability_involve = $('.disability_involve');
            for(let i = 0; i <= disability_involve.length; i++){    
                let disability_involveIndex = String(disability_involve[i].innerHTML);
                if(disability_involve[i].getAttribute('value') == {!! json_encode($program->disability_involve) !!}){
                    $('.disability_involve').eq(i).attr('selected', '');
                    break; 
                }
            }
        });
    </script>

@endsection