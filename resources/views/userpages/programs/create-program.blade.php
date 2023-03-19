@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
<button onclick="window.location='{{ route('program.management', 'A-Z') }} ';" class="btn btn-primary buttons mb-4">Back</button>

    <div class="container-fluid px-4">
        
        <form action="{{ route('program.save') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="mb-4"><b>Create PWD Program</b></h1>
                </div>
                <div class="col-lg-4">
                    <div class="col-lg">
                        <label class="title-detail">Upload Program Memo :</label>
                        <span class="text-danger">* Required</span>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input " id="customFile" name="memo[]" accept="image/*" required multiple>
                            <label class="custom-file-label" for="customFile">Scan Document (any image format)</label>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="row mb-2">
                <div class="col-lg-8">
                    <label class="title-detail">Program Title :  </label>
                    <span class="text-danger">* Required</span>
                    <input name="program_title" class="form-control" type="text" placeholder="Program Title" value="{{ old('program_title') }}" required>
                    @if ($errors->has('program_title'))
                        <span class="text-danger">{{ $errors->first('program_title') }}</span>
                    @endif
                </div>
                <div class="col-lg-4">
                    <label class="title-detail">Program Type</label>
                    <span class="text-danger">* Required</span>
                    <select name="program_type" id="program_type" class="custom-select required-field" required >
                        <option selected disabled value="">Select Program Type</option>
                        <option value="Education and Training" {{ old('program_type') == 'Education and Training' ? 'selected' : '' }}>Education and Training</option>
                        <option value="Health and Medical Treatment" {{ old('program_type') == 'Health and Medical Treatment' ? 'selected' : '' }}>Health and Medical Treatment</option>
                        <option value="Employment and Work" {{ old('program_type') == 'Employment and Work' ? 'selected' : '' }}>Employment and Work</option>
                        <option value="Cash Gifts or Grocery Packs" {{ old('program_type') == 'Cash Gifts or Grocery Packs' ? 'selected' : '' }}>Cash Gifts or Grocery Packs</option>
                        {{-- <option value="Grocery Packs" {{ old('program_type') == 'Grocery Packs' ? 'selected' : '' }}>Grocery Packs</option> --}}
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
                        <option value="1" {{ old('barangay_id') == '1' ? 'selected' : '' }}>All Barangays</option>
                        <option value="2" {{ old('barangay_id') == '2' ? 'selected' : '' }}>Baclaran</option>
                        <option value="3" {{ old('barangay_id') == '3' ? 'selected' : '' }}>Banay-Banay</option>
                        <option value="4" {{ old('barangay_id') == '4' ? 'selected' : '' }}>Banlic</option>
                        <option value="5" {{ old('barangay_id') == '5' ? 'selected' : '' }}>Bigaa</option>
                        <option value="6" {{ old('barangay_id') == '6' ? 'selected' : '' }}>Butong</option>
                        <option value="7" {{ old('barangay_id') == '7' ? 'selected' : '' }}>Casile</option>
                        <option value="8" {{ old('barangay_id') == '8' ? 'selected' : '' }}>Diezmo</option>
                        <option value="9" {{ old('barangay_id') == '9' ? 'selected' : '' }}>Gulod</option>
                        <option value="10" {{ old('barangay_id') == '10' ? 'selected' : '' }}>Mamatid</option>
                        <option value="11" {{ old('barangay_id') == '11' ? 'selected' : '' }}>Marinig</option>
                        <option value="12" {{ old('barangay_id') == '12' ? 'selected' : '' }}>Niugan</option>
                        <option value="13" {{ old('barangay_id') == '13' ? 'selected' : '' }}>Pittland</option>
                        <option value="14" {{ old('barangay_id') == '14' ? 'selected' : '' }}>Pulo</option>
                        <option value="15" {{ old('barangay_id') == '15' ? 'selected' : '' }}>Sala</option>
                        <option value="16" {{ old('barangay_id') == '16' ? 'selected' : '' }}>San Isidro</option>
                        <option value="17" {{ old('barangay_id') == '17' ? 'selected' : '' }}>Barangay I Poblacion</option>
                        <option value="18" {{ old('barangay_id') == '18' ? 'selected' : '' }}>Barangay II Poblacion</option>
                        <option value="19" {{ old('barangay_id') == '19' ? 'selected' : '' }}>Barangay III Poblacion</option>
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
                        <option value="All Disabilities" {{ old('disability_involve') == 'All All Disabilities' ? 'selected' : '' }}>All Disabilities</option>
                        <option value="Deaf/Hard of hearing" {{ old('disability_involve') == 'Deaf/Hard of hearing' ? 'selected' : '' }}>Deaf/Hard of hearing</option>
                        <option value="Intellecttual Disability" {{ old('disability_involve') == 'Intellecttual Disability' ? 'selected' : '' }}>Intellecttual Disability</option>
                        <option value="Learning Disability imparement" {{ old('disability_involve') == 'Learning Disability imparement' ? 'selected' : '' }}>Learning Disability imparement</option>
                        <option value="Mental Disability" {{ old('disability_involve') == 'Mental Disability' ? 'selected' : '' }}>Mental Disability</option>
                        <option value="Physical Disability" {{ old('disability_involve') == 'Physical Disability' ? 'selected' : '' }}>Physical Disability</option>
                        <option value="Psychosocial Disability" {{ old('disability_involve') == 'Psychosocial Disability' ? 'selected' : '' }}>Psychosocial Disability</option>
                        <option value="Speech and Language" {{ old('disability_involve') == 'Speech and Language' ? 'selected' : '' }}>Speech and Language</option>
                        <option value="Visual Disablity" {{ old('disability_involve') == 'Visual Disablity' ? 'selected' : '' }}>Visual Disablity</option>
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
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <label class="title-detail">Program Description :</label>
                    <textarea name="program_description" class="form-control"  cols="10" rows="7">{{ old('program_description') }}</textarea>
                    @if ($errors->has('program_description'))
                    <span class="text-danger">{{ $errors->first('program_description') }}</span>
                    @endif
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-success buttons">Create</button>
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

        document.getElementById('program_type').addEventListener('change', function() {
        if (this.value == "Cash Gifts") {
            document.getElementById('cash').disabled = false;
        } else {
            document.getElementById('cash').disabled = true;
        }
    });

        
    });
</script>
@endsection