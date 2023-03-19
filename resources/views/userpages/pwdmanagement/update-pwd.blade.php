@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
<a href="{{ route('view.pwd', $pwd->pwd_id) }}" class="btn btn-primary buttons mb-3">Back</a><br><br>   
    <div class="container-fluid px-4">  
        <form action="{{ route('update.pwd.save', $pwd->pwd_id) }}" method="POST">
            @csrf
            @method('PUT')
            <h3><b>Update Personal Information</b></h3><hr> 
            <div class="row row-form">
                <div class="col-lg-3 mt-1">
                    <label class="title-detail" >Last Name</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" value="{{$pwd->last_name}}" required>
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">First name</label>
                    <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" value="{{$pwd->first_name}}" required>
                </div>
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" placeholder="Enter Middle Name" value="{{$pwd->middle_name}}">
                </div>
                <div class="col-lg-2 mt-1">
                    <label class="title-detail">Suffix</label>
                    <input type="text" name="suffix" class="form-control" placeholder="Enter Suffix" value="{{$pwd->suffix}}">
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Birth Date</label>
                    <input type="date" class="form-control" name="birthday" value="{{ $pwd->birthday }}" required>
                </div>       
                <div class="col-lg-5 mt-1">
                    <label class="title-detail">Religion</label>
                    <input type="text" class="form-control" name="religion" placeholder="Enter Religion" value="{{ $pwd->religion }}" required>
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Ethnic Group</label>
                    <input type="text" class="form-control" name="ethnic_group" placeholder="Enter Ethic Group (Optional)" value="{{ $pwd->ethnic_group }}">
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Sex</label>
                    <select name="sex" class="custom-select" value="{{ old('sex') }}" required>
                        <option selected disabled value="">Select gender</option>
                        <option value="Male" class="sex" >Male</option>  
                        <option value="Female" class="sex" >Female</option>
                    </select>
                </div>
                <div class="col-lg-5 mt-1">
                    <label class="title-detail">Civil Status</label>
                    <select name="civil_status" class="custom-select" required>
                        <option selected disabled value="">Select civil status</option>
                        <option value="Single" class="civil-status" >Single</option>
                        <option value="Married" class="civil-status" >Married</option>
                        <option value="Widow/er" class="civil-status" >Widow/er</option>
                        <option value="Seperated" class="civil-status" >Seperated</option>
                        <option value="Co-habitation(Live-in)" class="civil-status" >Co-habitation(Live-in)</option>
                    </select>
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Blood Type</label>
                    <select name="blood_type" class="custom-select" required>
                        <option selected disabled value="">Select Blood type</option>
                        <option value="A+" class="blood-type" >A+</option>
                        <option value="A-" class="blood-type" >A-</option>
                        <option value="B+" class="blood-type" >B+</option>
                        <option value="B" class="blood-type" >B-</option>
                        <option value="AB+" class="blood-type" >AB+</option>
                        <option value="AB-" class="blood-type" >AB-</option>
                        <option value="O+" class="blood-type" >O+</option>
                        <option value="O-" class="blood-type" >O-</option>
                    </select>
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Type of Disability</label>
                    <select name="disability_type" class="custom-select" required>
                        <option selected disabled value="">Select disability type</option>
                        <option value="Deaf/Hard of hearing" class="disability-type">Deaf/Hard of hearing</option>
                        <option value="Intellecttual Disability" class="disability-type">Intellecttual Disability</option>
                        <option value="Learning Disability imparement" class="disability-type">Learning Disability imparement</option>
                        <option value="Mental Disability" class="disability-type">Mental Disability</option>
                        <option value="Physical Disability" class="disability-type">Physical Disability</option>
                        <option value="Psychosocial Disability" class="disability-type">Psychosocial Disability</option>
                        <option value="Speech and Language" class="disability-type">Speech and Language</option>
                        <option value="Visual Disablity" class="disability-type">Visual Disablity</option>
                    </select>
                </div>
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Cause of Disablity</label>
                    <select name="disability_cause" class="custom-select" required>
                        <option selected disabled value="">Select disability cause</option>
                        <option value="Acquired" class="disability-cause">Acquired</option>
                        <option value="Cancer" class="disability-cause">Cancer</option>
                        <option value="Chronic Illness" class="disability-cause">Chronic Illness</option>
                        <option value="Congenital/Inborn" class="disability-cause">Congenital/Inborn</option>
                        <option value="Injury" class="disability-cause">Injury</option>
                        <option value="Rare Disease" class="disability-cause">Rare Disease</option>
                    </select>
                </div>
                <div class="col-lg-5 mt-1">
                    <label class="title-detail">Name of Disability</label>
                    <input type="text" class="form-control" name="disability_name" placeholder="Enter Disability Name" value="{{ $pwd->disability_name }}">
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-5 mt-1">
                    <label class="title-detail">Address</label>
                    <input type="text" class="form-control" placeholder="Block/Lot/House No./Purok/Street/Subdivision/Village" name="address" value="{{ $pwd->address }}" required>
                </div>
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Barangay</label>
                    <select name="barangay_id" class="custom-select" required readonly>
                        <option selected disabled value="">Select Barangay</option>
                        <option value="2" class="barangay">Baclaran</option>
                        <option value="3" class="barangay">Banay-Banay</option>
                        <option value="4" class="barangay">Banlic</option>
                        <option value="5" class="barangay">Bigaa</option>
                        <option value="6" class="barangay">Butong</option>
                        <option value="7" class="barangay">Casile</option>
                        <option value="8" class="barangay">Diezmo</option>
                        <option value="9" class="barangay">Gulod</option>
                        <option value="10" class="barangay">Mamatid</option>
                        <option value="11" class="barangay">Marinig</option>
                        <option value="12" class="barangay">Niugan</option>
                        <option value="13" class="barangay">Pittland</option>
                        <option value="14" class="barangay">Pulo</option>
                        <option value="15" class="barangay">Sala</option>
                        <option value="16" class="barangay">San Isidro</option>
                        <option value="17" class="barangay">Barangay I Poblacion</option>
                        <option value="18" class="barangay">Barangay II Poblacion</option>
                        <option value="19" class="barangay">Barangay III Poblacion</option>
                    </select>
                </div>
                <div class="col-lg-2 mt-1">
                    <label class="title-detail">City</label>
                    <h6 style="margin-top: 8px">Cabuyao City</h6>
                </div>
                <div class="col-lg-2 mt-1">
                    <label class="title-detail">Province</label>
                    <h6>Laguna</h6>
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Phone No.</label>
                    <input type="number" class="form-control" name="phone_number" placeholder="Enter Phone Number" value="{{ $pwd->phone_number }}" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==11) return false;" minlength="11" required>
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Telephone No.</label>
                    <input type="text" class="form-control" name="telephone_number" placeholder="Enter Telephone Number (Optional)" value="{{ $pwd->telephone_number }}">
                </div>
               
                <div class="col-lg-5 mt-1">
                    <label class="title-detail">Email Address</label>
                    <input type="text" class="form-control" name="email" placeholder="Enter Email Address (Optional)" value="{{ $pwd->email }}">
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-5 mt-1">
                    <label class="title-detail">Educational Attaintment</label>
                    <select name="educational_attainment" class="custom-select" >
                        <option selected disabled value="">Select Educational Attaintment</option>
                        <option value="None" class="educational-attainment" >None</option>
                        <option value="Elementary Education" class="educational-attainment" >Elementary Education</option>
                        <option value="High School Education" class="educational-attainment" >High School Education</option>
                        <option value="College" class="educational-attainment" >College</option>
                        <option value="Postgraduate Program" class="educational-attainment" >Postgraduate Program</option>
                        <option value="Non-Formal Education" class="educational-attainment">Non-Formal Education</option>
                        <option value="Vocational" class="educational-attainment">Vocational</option>
                    </select>
                </div>
                <div class="col-lg-7 mt-1">
                    <label class="title-detail">Status of Employment</label>
                    <select name="employment_status" class="custom-select">
                        <option selected disabled value=""> select</option>
                        <option class="employment-status" value="Employed"  >Employed</option>
                        <option class="employment-status" value="Unemployed"  >Unemployed</option>
                        <option class="employment-status" value="Self-Employed"  >Self-Employed</option>
                    </select>
              </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Category of Employment</label>
                    <select name="employment_category" class="custom-select">
                        <option selected disabled value="">  select</option>
                        <option value="Goverment" class="employment-category" >Goverment</option>
                        <option value="Private" class="employment-category" >Private</option>          
                    </select>
                </div>
                <div class="col-lg-3 mt-1">
                    <label class="title-detail">Type of Employment</label>
                    <select name="employment_type" class="custom-select">
                        <option selected disabled value="">  select</option>
                        <option value="Permanent" class="employment-type" >Permanent</option>
                        <option value="Regular" class="employment-type" >Regular</option>
                        <option value="Contractual" class="employment-type">Contractual</option>
                        <option value="Casual" class="employment-type">Casual</option>
                        <option value="Self-employed" class="employment-type">Self-employed</option>        
                        <option value="Seasonal" class="employment-type">Seasonal</option>    
                        <option value="Emergency" class="employment-type" >Emergency</option>      
                    </select>
                </div>
                <div class="col-lg-6 mt-1">
                    <label class="title-detail">Occupation</label>
                    <input type="text" class="form-control" name="occupation" placeholder="Enter Occupation" value="{{ $pwd->occupation }}">
                </div>
            </div><br>
            <h4><b>Organization Information</b></h4>
            <hr>
            <div class="row row-form">
                <div class="col-lg"> 
                    <label class="title-detail">Organization Affiliated</label>
                    <input type="text" class="form-control" name="organization_affliated" placeholder="Enter Affiliated (Optional)" value="{{ $pwd->organization_affliated }}">
                </div>
                <div class="col-lg"> 
                    <label class="title-detail">Contact Person</label>
                    <input type="text" class="form-control" name="organization_contact_person" placeholder="Enter Contact Person (Optional)" value="{{ $pwd->organization_contact_person }}" >
                </div>
                <div class="col-lg"> 
                    <label class="title-detail">Office Address</label>
                    <input type="text" class="form-control" name="organization_office_address" placeholder="Enter Office Address (Optional)" value="{{ $pwd->organization_office_address }}">
                </div>
                <div class="col-lg"> 
                    <label class="title-detail">Tel. Nos</label>
                    <input type="text" class="form-control" name="organization_telephone_number" placeholder="Enter Tel No. (Optional)" value="{{ $pwd->organization_telephone_number }}">
                </div>
            </div><br>
            <h4><b>ID Reference No.</b></h4>
            <hr>
            <div class="row row-form">
                <div class="col-lg"> 
                    <label class="title-detail">SSS No.</label>
                    <input type="text" class="form-control" name="sss_number" placeholder="Enter SSS No. (Optional)" value="{{ $pwd->sss_number }}">
                </div>
                <div class="col-lg"> 
                    <label class="title-detail">SSS No.</label>
                    <input type="text" class="form-control"name="gsis_number" placeholder="Enter SSS No. (Optional)" value="{{ $pwd->gsis_number }}">
                </div>
                <div class="col-lg"> 
                    <label class="title-detail">SSS No.</label>
                    <input type="text" class="form-control" name="pagibig_number" placeholder="Enter SSS No. (Optional)" value="{{ $pwd->pagibig_number }}">
                </div>
                <div class="col-lg"> 
                    <label class="title-detail">SSS No. </label>
                    <input type="text" class="form-control" name="philhealth_number" placeholder="Enter SSS No. (Optional)" value="{{ $pwd->philhealth_number }}">
                </div>
            </div><br>
            <h4><b>Family Background</b></h4>
            <hr>
            <div class="row row-form">
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Father's Last Name</label>
                    <input type="text" class="form-control" name="father_last_name" placeholder="Enter Father's Last Name" value="{{ $pwd->father_last_name }}" required>
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Mother's Last Name</label>
                    <input type="text" class="form-control" name="mother_last_name" placeholder="Enter Mother's Last Name" value="{{ $pwd->mother_last_name }}" required>
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Guardian's Last Name</label>
                    <input type="text" class="form-control" name="guardian_last_name" placeholder="Enter Guardian's Last Name" value="{{ $pwd->guardian_last_name }}" >
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Father's First Name</label>
                    <input type="text" class="form-control" name="father_first_name" placeholder="Enter Father's First Name" value="{{ $pwd->father_first_name }}" required>
                </div>
             
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Mother's First Name</label>
                    <input type="text" class="form-control" name="mother_first_name" placeholder="Enter Mother's First Name" value="{{ $pwd->mother_first_name }}" required>
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Guardian's First Name</label>
                    <input type="text" class="form-control" name="guardian_first_name" placeholder="Enter Guardian's First Name" value="{{ $pwd->guardian_first_name }}">
                </div>
            </div>
            <div class="row row-form">
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Father's Middle Name</label>
                    <input type="text" class="form-control" name="father_middle_name" placeholder="Enter Father's Middle Name" value="{{ $pwd->father_middle_name }}">                     
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Mother's Middle Name</label>
                    <input type="text" class="form-control" name="mother_middle_name" placeholder="Enter Mother's Middle Name" value="{{ $pwd->mother_middle_name }}">
                  
                </div>
                <div class="col-lg-4 mt-1">
                    <label class="title-detail">Guardian's Middle Name</label>
                    <input type="text" class="form-control" name="guardian_middle_name" placeholder="Enter Guardian's Middle Name" value="{{ $pwd->guardian_middle_name }}">
                </div>
            </div><br>
            <div class="row">
                <div class="col-lg-12 text-right">
                    <button type="sumbit" class="btn btn-success buttons">Update</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function(){
            var sex = $('.sex');
            for(let i = 0; i <= sex.length; i++){    
                let sexIndex = String(sex[i].innerHTML);
                if(sexIndex == {!! json_encode($pwd->sex) !!}){
                    $('.sex').eq(i).attr('selected', '');
                    break;
                }
            }

            var civilStatus = $('.civil-status');
            for(let i = 0; i <= civilStatus.length; i++){    
                let civilStatusIndex = String(civilStatus[i].innerHTML);
                if(civilStatusIndex == {!! json_encode($pwd->civil_status) !!}){
                    $('.civil-status').eq(i).attr('selected', '');
                    break;
                }
            }

            var bloodType = $('.blood-type');
            for(let i = 0; i <= bloodType.length; i++){    
                let bloodTypeIndex = String(bloodType[i].innerHTML);
                if(bloodTypeIndex == {!! json_encode($pwd->blood_type) !!}){
                    $('.blood-type').eq(i).attr('selected', '');
                    break;
                }
            }

            var disabilityType = $('.disability-type');
            for(let i = 0; i <= disabilityType.length; i++){    
                let disabilityTypeIndex = String(disabilityType[i].innerHTML);
                if(disabilityTypeIndex == {!! json_encode($pwd->disability_type) !!}){
                    $('.disability-type').eq(i).attr('selected', '');
                    break;
                }
            }

            var disabilityCause = $('.disability-cause');
            for(let i = 0; i <= disabilityCause.length; i++){    
                let disabilityCauseIndex = String(disabilityCause[i].innerHTML);
                if(disabilityCauseIndex == {!! json_encode($pwd->disability_cause) !!}){
                    $('.disability-cause').eq(i).attr('selected', '');
                    break;
                }
            }

            
            var disabilityCause = $('.disability-cause');
            for(let i = 0; i <= disabilityCause.length; i++){    
                let disabilityCauseIndex = String(disabilityCause[i].innerHTML);
                if(disabilityCauseIndex == {!! json_encode($pwd->disability_cause) !!}){
                    $('.disability-cause').eq(i).attr('selected', '');
                    break;
                }
            }

            var barangay = $('.barangay');
            for(let i = 0; i <= barangay.length; i++){    
                let barangayIndex = String(barangay[i].innerHTML);
                if(barangayIndex == {!! json_encode($pwd->barangay->barangay_name) !!}){
                    $('.barangay').eq(i).attr('selected', '');
                    break;
                }
            }

            var educationalAttainment = $('.educational-attainment');
            for(let i = 0; i <= educationalAttainment.length; i++){    
                let educationalAttainmentIndex = String(educationalAttainment[i].innerHTML);
                if(educationalAttainmentIndex == {!! json_encode($pwd->educational_attainment) !!}){
                    $('.educational-attainment').eq(i).attr('selected', '');
                    break;
                }
            }
        
            var employmentStatus = $('.employment-status');
            for(let i = 0; i <= employmentStatus.length; i++){    
                let employmentStatusIndex = String(employmentStatus[i].innerHTML);
                if(employmentStatusIndex == {!! json_encode($pwd->employment_status) !!}){
                    $('.employment-status').eq(i).attr('selected', '');
                    break;
                }
            }
            
            var employmentCategory = $('.employment-category');
            for(let i = 0; i <= employmentCategory.length; i++){    
                let employmentCategoryIndex = String(employmentCategory[i].innerHTML);
                if(employmentCategoryIndex == {!! json_encode($pwd->employment_category) !!}){
                    $('.employment-category').eq(i).attr('selected', '');
                    break;
                }
            }

            var employmentType = $('.employment-type');
            for(let i = 0; i <= employmentType.length; i++){    
                let employmentTypeIndex = String(employmentType[i].innerHTML);
                if(employmentTypeIndex == {!! json_encode($pwd->employment_type) !!}){
                    $('.employment-type').eq(i).attr('selected', '');
                    break;
                }
            }
        });
    </script>
@endsection