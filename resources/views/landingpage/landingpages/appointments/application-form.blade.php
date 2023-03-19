@extends('landingpage.navigation.navigation')

@section('content')
@include('sweetalert::alert')
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .form-section{
            display: none;
        }
        .form-section.current{
            display: inline;
        }
        .parsley-errors-list{
            margin-left: -40px;
            color:red;
        }
        span{
            font-size:13px;
        }
        li{
            font-size: 14px;
        }
    </style>
    </head>
          <div class="row">
            <div class="col-md-12 ">
                <div class=" px-4 py-3 mt-1">
                    <h1><b>Appointment for PWD Application</b></h1>      
                    <h6 class="text-danger">Note : If the input field has an asterisk (*) it means required to fill out</h6>             
                    <div class="nav nav-fill my-3">
                        <label class="nav-link shadow-sm step0 border ml-2 ">Personal Information</label>
                        <label class="nav-link shadow-sm step1 border ml-2 " >Reference Information</label>
                        <label class="nav-link shadow-sm step2 border ml-2 " >Documents Uploading</label>
                    </div>
                    <form action="{{ route('new-applicant.create') }}" method="POST" enctype="multipart/form-data" class="form">
                        @csrf
                        <div class="form-section">
                            <div class="row">
                                <div class="col-lg-9    ">
                                    <h3 class="mt-4"><b>Personal Information</b></h3>
                                </div>  
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1" >Last Name </label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" name="last_name" class="form-control required-field" placeholder="Enter Last Name" value="{{old('last_name')}}" required>
                                    @if ($errors->has('last_name'))
                                        <span class="text-danger">{{  $errors>first('last_name') }}</span>
                                    @endif
                
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">First name</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" name="first_name" class="form-control required-field" placeholder="Enter First Name" value="{{ old('first_name') }}" required>
                
                                    @if ($errors->has('first_name'))
                                        <span class="text-danger">{{  $errors>first('first_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" placeholder="Enter Middle Name" value="{{ old('middle_name') }}">
                                </div>
                                <div class="col-lg-2 mt-1">
                                    <label class="title-detail mt-1">Suffix</label>
                                    <input type="text" name="suffix" class="form-control" placeholder="Enter Suffix" alue="{{ old('suffix') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Birth Date</label>
                                    <span class="text-danger h6">*</span> 
                                    <input type="date" class="form-control required-field" id="birthday-applicant" name="birthday" value="{{ old('birthday') }}" required>
                                    @if ($errors->has('birthday'))
                                        <span class="text-danger">{{  $errors>first('birthday') }}</span>
                                    @endif
                                </div>       
                                <div class="col-lg-5 mt-1">
                                    <label class="title-detail mt-1">Religion</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control required-field" name="religion" placeholder="Enter Religion" value="{{ old('religion') }}" required>
                                    @if ($errors->has('religion'))
                                        <span class="text-danger">{{  $errors>first('religion') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">Ethnic Group</label>
                                    <input type="text" class="form-control" name="ethnic_group" placeholder="Enter Ethic Group (Optional)" value="{{ old('ethnic_group') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Sex</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="sex" class="custom-select required-field" value="{{ old('sex') }}" required>
                                        <option selected disabled value="">Select gender</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>  
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                
                                    @if ($errors->has('sex'))
                                        <span class="text-danger">{{  $errors>first('sex') }}</span>    
                                    @endif
                                </div>
                                <div class="col-lg-5 mt-1">
                                    <label class="title-detail mt-1">Civil Status</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="civil_status" class="custom-select required-field" required>
                                        <option selected disabled value="">Select civil status</option>
                                        <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widow/er" {{ old('civil_status') == 'Widow/er' ? 'selected' : '' }}>Widow/er</option>
                                        <option value="Seperated" {{ old('civil_status') == 'Seperated' ? 'selected' : '' }}>Seperated</option>
                                        <option value="Co-habitation(Live-in)" {{ old('civil_status') == 'Co-habitation(Live-in)' ? 'selected' : '' }}>Co-habitation(Live-in)</option>
                                    </select>
                                    @if ($errors->has('civil_status'))
                                        <span class="text-danger">{{  $errors>first('civil_status') }}</span>    
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">Blood Type</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="blood_type" class="custom-select required-field" required>
                                        <option selected disabled value="">Select Blood type</option>
                                        <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    @if ($errors->has('blood_type'))
                                        <span class="text-danger">{{  $errors>first('blood_type') }}</span>    
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">Type of Disability</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="disability_type" class="custom-select required-field" required>
                                        <option selected disabled value="">Select disability type</option>
                                        <option value="Deaf/Hard of hearing" {{ old('disability_type') == 'Deaf/Hard of hearing' ? 'selected' : '' }}>Deaf/Hard of hearing</option>
                                        <option value="Intellectual Disability" {{ old('disability_type') == 'Intellecttual Disability' ? 'selected' : '' }}>Intellecttual Disability</option>
                                        <option value="Learning Disability imparement" {{ old('disability_type') == 'Learning Disability imparement' ? 'selected' : '' }}>Learning Disability imparement</option>
                                        <option value="Mental Disability" {{ old('disability_type') == 'Mental Disability' ? 'selected' : '' }}>Mental Disability</option>
                                        <option value="Physical Disability" {{ old('disability_type') == 'Physical Disability' ? 'selected' : '' }}>Physical Disability</option>
                                        <option value="Psychosocial Disability" {{ old('disability_type') == 'Psychosocial Disability' ? 'selected' : '' }}>Psychosocial Disability</option>
                                        <option value="Speech and Language" {{ old('disability_type') == 'Speech and Language' ? 'selected' : '' }}>Speech and Language</option>
                                        <option value="Visual Disablity" {{ old('disability_type') == 'Visual Disablity' ? 'selected' : '' }}>Visual Disablity</option>
                                    </select>
                                    @if ($errors->has('disability_type'))
                                        <span class="text-danger">{{  $errors>first('disability_type') }}</span>        
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Cause of Disablity</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="disability_cause" class="custom-select required-field" required>
                                        <option selected disabled value="">Select disability cause</option>
                                        <option value="Acquired" {{ old('disability_cause') == 'Acquired' ? 'selected' : '' }}>Acquired</option>
                                        <option value="Cancer" {{ old('disability_cause') == 'Cancer' ? 'selected' : '' }}>Cancer</option>
                                        <option value="Chronic Illness" {{ old('disability_cause') == 'Chronic Illness' ? 'selected' : '' }}>Chronic Illness</option>
                                        <option value="Congenital/Inborn" {{ old('disability_cause') == 'Congenital/Inborn' ? 'selected' : '' }}>Congenital/Inborn</option>
                                        <option value="Injury" {{ old('disability_cause') == 'Injury' ? 'selected' : '' }}>Injury</option>
                                        <option value="Rare Disease" {{ old('disability_cause') == 'Rare Disease' ? 'selected' : '' }}>Rare Disease</option>
                                    </select>
                                    @if ($errors->has('disability_cause'))
                                        <span class="text-danger">{{  $errors>first('disability_cause') }}</span>    
                                    @endif
                                </div>
                                <div class="col-lg-5 mt-1">
                                    <label class="title-detail mt-1">Name of Disability</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control required-field" name="disability_name" placeholder="Enter Disability Name" value="{{ old('disability_name') }}" required>
                                    @if ($errors->has('disability_name'))
                                        <span class="text-danger">{{  $errors>first('disability_name') }}</span>        
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 mt-1">
                                    <label class="title-detail mt-1">Address</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control required-field" placeholder="Block/Lot/House No./Purok/Street/Subdivision/Village" name="address" value="{{ old('address') }}" required>
                                    @if ($errors->has('address'))
                                        <span class="text-danger">{{  $errors>first('address') }}</span>        
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Barangay</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="barangay_id" class="custom-select required-field" required >
                                        <option selected disabled value="">Select Barangay</option>
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
                                        <span class="text-danger">{{  $errors>first('barangay_id') }}</span>                            
                                    @endif
                                </div>
                                <div class="col-lg-2 mt-1">
                                    <label class="title-detail mt-1">City</label>
                                    <h6 style="margin-top: 8px">Cabuyao City</h6>
                                </div>
                                <div class="col-lg-2 mt-1">
                                    <label class="title-detail mt-1">Province</label>
                                    <h6>Laguna</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Phone No.</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="number" class="form-control required-field" name="phone_number" placeholder="Enter Phone Number" value="{{ old('phone_number') }}" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==11) return false;" minlength="11" required>
                                    @if ($errors->has('phone_number'))
                                        <span class="text-danger">{{  $errors>first('phone_number') }}</span>          
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">Telephone No.</label>
                                    <input type="text" class="form-control" name="telephone_number" placeholder="Enter Telephone Number (Optional)" value="{{ old('telephone_number') }}">
                                </div>
                            
                                <div class="col-lg-5 mt-1">
                                    <label class="title-detail mt-1">Email Address</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="email" class="form-control required-field" name="email" placeholder="Enter Email Address" value="{{ old('email') }}" required>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{  $errors>first('email') }}</span>          
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 mt-1">
                                    <label class="title-detail mt-1">Educational Attaintment</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="educational_attainment" class="custom-select required-field" required >
                                        <option selected disabled value="">Select Educational Attaintment</option>
                                        <option value="None" {{ old('educational_attainment') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="Elementary Education" {{ old('educational_attainment') == 'Elementary Education' ? 'selected' : '' }}>Elementary Education</option>
                                        <option value="High School Education" {{ old('educational_attainment') == 'High School Education' ? 'selected' : '' }}>High School Education</option>
                                        <option value="College" {{ old('educational_attainment') == 'College' ? 'selected' : '' }}>College</option>
                                        <option value="Postgraduate Program" {{ old('educational_attainment') == 'Postgraduate Program' ? 'selected' : '' }}>Postgraduate Program</option>
                                        <option value="Non-Formal Education" {{ old('educational_attainment') == 'Non-Formal Education' ? 'selected' : '' }}>Non-Formal Education</option>
                                        <option value="Vocational" {{ old('educational_attainment') == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                    </select>
                                    @if ($errors->has('educational_attainment'))
                                        <span class="text-danger">{{  $errors>first('educational_attainment') }}</span>              
                                    @endif
                                </div>
                                <div class="col-lg-7 mt-1">
                                    <label class="title-detail mt-1">Status of Employment</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="employment_status" class="custom-select required-field" required>
                                        <option selected disabled value="">Select Status of Employment</option>
                                        <option value="Employed" {{ old('employment_status') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                        <option value="Unemployed" {{ old('employment_status') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                        <option value="Self-Employed" {{ old('employment_status') == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                    </select>
                                    @if ($errors->has('employment_status'))
                                    <span class="text-danger">{{  $errors>first('employment_status') }}</span>              
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Category of Employment</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="employment_category" class="custom-select required-field" required>
                                        <option selected disabled value="">Please Select </option>
                                        <option value="None" {{ old('employment_category') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="Goverment" {{ old('employment_category') == 'Goverment' ? 'selected' : '' }}>Goverment</option>
                                        <option value="Private" {{ old('employment_category') == 'Private' ? 'selected' : '' }}>Private</option>          
                                    </select>
                                    @if ($errors->has('employment_category'))
                                        <span class="text-danger" s yle="fon-size: 10px; ">{{ $errors->first('employment_category') }}</span>          
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-1">
                                    <label class="title-detail mt-1">Type of Employment</label>
                                    <span class="text-danger h6">*</span>
                                    <select name="employment_type" class="custom-select required-field" required>
                                        <option selected disabled value="">Please Select</option>
                                        <option value="None" {{ old('employment_type') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="Permanent" {{ old('employment_type') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="Regular" {{ old('employment_type') == 'Regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="Contractual" {{ old('employment_type') == 'Contractual' ? 'selected' : '' }}>Contractual</option>
                                        <option value="Casual" {{ old('employment_type') == 'Casual' ? 'selected' : '' }}>Casual</option>
                                        <option value="Self-employed" {{ old('employment_type') == 'Self-employed' ? 'selected' : '' }}>Self-employed</option>        
                                        <option value="Seasonal" {{ old('employment_type') == 'Seasonal' ? 'selected' : '' }}>Seasonal</option>    
                                        <option value="Emergency" {{ old('employment_type') == 'Emergency' ? 'selected' : '' }}>Emergency</option>      
                                    </select>
                                    @if ($errors->has('employment_type'))
                                        <span class="text-danger" s yle="fon-size: 10px; ">{{ $errors->first('employment_type') }}</span>          
                                    @endif
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label class="title-detail mt-1">Occupation</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control required-field" name="occupation" placeholder="Enter Occupation" value="{{ old('occupation') }}" required>
                                    @if ($errors->has('occupation'))
                                        <span class="text-danger">{{  $errors>first('occupation') }}</span>          
                                    @endif
                                </div>
                            </div><br>
                        </div>
                        <div class="form-section">
                            <h4><b>Organization Information</b></h4>
                            <hr>
                            <div class="row">
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">Organization Affiliated</label>
                                    <input type="text" class="form-control" name="organization_affliated" placeholder="Enter Affiliated (Optional)" value="{{ old('organization_affliated') }}">
                                </div>
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">Contact Person</label>
                                    <input type="text" class="form-control" name="organization_contact_person" placeholder="Enter Contact Person (Optional)" value="{{ old('organization_contact_person') }}" >
                                </div>
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">Office Address</label>
                                    <input type="text" class="form-control" name="organization_office_address" placeholder="Enter Office Address (Optional)" value="{{ old('organization_office_address') }}">
                                </div>
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">Tel. Nos</label>
                                    <input type="text" class="form-control" name="organization_telephone_number" placeholder="Enter Tel No. (Optional)" value="{{ old('organization_telephone_number') }}">
                                </div>
                            </div><br>
                            <h4><b>ID Reference No.</b></h4>
                            <hr>
                            <div class="row">
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">SSS No.</label>
                                    <input type="text" class="form-control" name="sss_number" placeholder="Enter SSS No. (Optional)" value="{{ old('sss_number') }}">
                                </div>
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">SSS No.</label>
                                    <input type="text" class="form-control"name="gsis_number" placeholder="Enter SSS No. (Optional)" value="{{ old('gsis_number') }}">
                                </div>
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">SSS No.</label>
                                    <input type="text" class="form-control" name="pagibig_number" placeholder="Enter SSS No. (Optional)" value="{{ old('pagibig_number') }}">
                                </div>
                                <div class="col-lg"> 
                                    <label class="title-detail mt-1">SSS No. </label>
                                    <input type="text" class="form-control" name="philhealth_number" placeholder="Enter SSS No. (Optional)" value="{{ old('philhealth_number') }}">
                                </div>
                            </div><br>
                            <h4><b>Family Background</b></h4>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">Father's Last Name</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control" name="father_last_name" placeholder="Enter Father's Last Name" value="{{ old('father_last_name') }}" required>
                                    @if ($errors->has('father_last_name'))
                                        <span class="text-danger">{{  $errors>first('father_last_name') }}</span>
                                    @endif
                                    <label class="title-detail mt-1">Father's First Name</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control" name="father_first_name" placeholder="Enter Father's First Name" value="{{ old('father_first_name') }}" required>
                                    @if ($errors->has('father_first_name'))
                                        <span class="text-danger">{{  $errors>first('father_first_name') }}</span>
                                    @endif
                                    <label class="title-detail mt-1">Father's Middle Name</label>
                                    <input type="text" class="form-control" name="father_middle_name" placeholder="Enter Father's Middle Name" value="{{ old('father_middle_name') }}">                     
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">Mother's Last Name</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control" name="mother_last_name" placeholder="Enter Mother's Last Name" value="{{ old('mother_last_name') }}" required>
                                    @if ($errors->has('mother_last_name'))
                                        <span class="text-danger">{{  $errors>first('mother_last_name') }}</span>
                                    @endif
                                    <label class="title-detail mt-1">Mother's First Name</label>
                                    <span class="text-danger h6">*</span>
                                    <input type="text" class="form-control" name="mother_first_name" placeholder="Enter Mother's First Name" value="{{ old('mother_first_name') }}" required>
                                    @if ($errors->has('mother_first_name'))
                                        <span class="text-danger">{{  $errors>first('mother_first_name') }}</span>
                                    @endif
                                    <label class="title-detail mt-1">Mother's Middle Name</label>
                                    <input type="text" class="form-control" name="mother_middle_name" placeholder="Enter Mother's Middle Name" value="{{ old('mother_middle_name') }}">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label class="title-detail mt-1">Guardian's Last Name</label>
                                    <input type="text" class="form-control" name="guardian_last_name" placeholder="Enter Guardian's Last Name" value="{{ old('guardian_last_name') }}" >
                                    @if ($errors->has('guardian_last_name'))
                                        <span class="text-danger" s yle="fon-size: 10px; ">{{ $errors->first('guardian_last_name') }}</span>
                                    @endif
                                    <label class="title-detail mt-1">Guardian's First Name</label>
                                    <input type="text" class="form-control" name="guardian_first_name" placeholder="Enter Guardian's First Name" value="{{ old('guardian_first_name') }}">
                                    @if ($errors->has('guardian_first_name'))
                                        <span class="text-danger" s yle="fon-size: 10px; ">{{ $errors->first('guardian_first_name') }}</span>
                                    @endif
                                    <label class="title-detail mt-1">Guardian's Middle Name</label>
                                    <input type="text" class="form-control" name="guardian_middle_name" placeholder="Enter Guardian's Middle Name" value="{{ old('guardian_middle_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <label class="text-danger m-auto">*Note : You can only upload pdf scan copy of documents </label>
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex flex-row-reverse">
                                        <div class="p-2">
                                            <label class="title-detail mt-1">Available Appointment Dates </label>
                                            <span class="text-danger h6">*</span>
                                            <input type="text" class="form-control" id="datepicker-appointment" name="appointment_date" placeholder="Click to choose date" readonly="false" required>
                                            @if ($errors->has('appointment_date'))
                                            <span class="text-danger">{{  $errors>first('appointment_date') }}</span>
                                            @endif
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <label class="title-detail mt-1">Medical Certification</label>
                                    <span class="text-danger h6">*</span>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="med_cert[]" accept="application/pdf" required multiple>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                        @if ($errors->has('med_cert'))
                                            <span class="text-danger">{{  $errors>first('med_cert') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="title-detail mt-1">Voter's ID or Certification</label>
                                    <span class="text-danger h6">*</span>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="voters[]" accept="application/pdf" required multiple >
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                        @if ($errors->has('voters'))
                                            <span class="text-danger">{{  $errors>first('voters') }}</span>
                                        @endif                                        
                                      </div>
                                </div>       
                                <div class="col-lg-4">
                                    <label class="title-detail mt-1">Authorization(Optional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="authorization[]" accept="application/pdf" multiple>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                        @if ($errors->has('authorization'))
                                            <span class="text-danger">{{  $errors>first('authorization') }}</span>
                                        @endif 
                                        
                                      </div>
                                </div>                            
                            </div>
                           
                        </div>
                        <div class="form-navigation mt-3">
                            <button type="button" class="previous btn btn-primary float-left buttons">&lt; Previous</button>
                            <button type="button" class="next btn btn-primary float-right buttons">Next &gt;</button>
                            <button type="submit" class="btn btn-success float-right buttons">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
            if(dd<10){
                    dd='0'+dd
                } 
                if(mm<10){
                    mm='0'+mm
                } 

            today = yyyy+'-'+mm+'-'+dd;
            document.getElementById("birthday-applicant").setAttribute("max", today);

            var $sections=$('.form-section');

            function navigateTo(index){
                $sections.removeClass('current').eq(index).addClass('current');
                $('.form-navigation .previous').toggle(index>0);
                var atTheEnd = index >= $sections.length - 1;
                $('.form-navigation .next').toggle(!atTheEnd);
                $('.form-navigation [Type=submit]').toggle(atTheEnd);
         
                const step= document.querySelector('.step'+index);
                step.style.backgroundColor="#17a2b8";
                step.style.color="white";
            }
            function curIndex(){
                return $sections.index($sections.filter('.current'));
            }
            $('.form-navigation .previous').click(function(){
                navigateTo(curIndex() - 1);
            });
            $('.form-navigation .next').click(function(){
                $('.form').parsley().whenValidate({
                    group:'block-'+curIndex()
                }).done(function(){
                    navigateTo(curIndex()+1);
                });
            });
            $sections.each(function(index,section){
                $(section).find(':input').attr('data-parsley-group','block-'+index);
            });
            navigateTo(0);
        });
    </script>
@endsection