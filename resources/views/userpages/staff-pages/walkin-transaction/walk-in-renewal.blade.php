@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
<a href="{{ route('view.pwd', $pwd->pwd_id) }}" class="btn btn-primary buttons mb-3">Back</a><br><br>   

<div class="container-fluid">
    <h1 class="text-center"><b>Renewal of ID </b>(Walk In)</h1><br><br>
    <div class="row">
        <div class="col-10">
            <h4 ><b>Personal Information</b></h4>
        </div>
        <div class="col-2">
        @if($pwd->pwd_status->id_expiration < date('Y-m-d'))
            <input type="button" value="Upload Documents" class="btn btn-success" data-toggle="modal" data-target="#documents">
        @else
            <a href="" id="generate-id" class="btn btn-success buttons">Generate ID</a>
        @endif
        </div>
        
    </div><hr>
    <div class="row">
        <div class="col-xl d-flex">  
            <label class="title-detail">Last Name : </label>
            <p class="detail-value">{{ $pwd->last_name}}  </p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">First name : </label>
            <p class="detail-value">{{ $pwd->first_name}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Middle Name : </label>
            <p class="detail-value">{{ $pwd->middle_name}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Suffix : </label>
            <p class="detail-value">{{ ($pwd->suffix == null)? "None" : $pwd->suffix}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Sex : </label>
            <p class="detail-value">{{ $pwd->sex}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Civil Status : </label>
            <p class="detail-value">{{ $pwd->civil_status}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Age : </label>
            <p class="detail-value">{{ $pwd->age}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Date of Birth : </label>
            <p class="detail-value">{{date('F d, Y', strtotime($pwd->birthday))}}</p>
    
        </div>       
        <div class="col-xl d-flex">
            <label class="title-detail">Religion : </label>
            <p class="detail-value">{{ $pwd->religion}}</p>
        </div>
       
       
    </div>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Ethnic Group : </label>
            <p class="detail-value">{{ ($pwd->ethnic_group == null)? "None" : $pwd->ethnic_group}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Blood Type : </label>
            <p class="detail-value">{{ $pwd->blood_type}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Type of Disability : </label>
            <p class="detail-value">{{ $pwd->disability_type}}</p>
         
        </div>
        
    </div>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Cause of Disablity : </label>
            <p class="detail-value">{{ $pwd->disability_cause}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Cause of Disablity : </label>
            <p class="detail-value">{{ $pwd->disability_name}}</p>
        </div>
        <div class="col-xl d-flex-xl d-flex">
            <label class="title-detail">Address : </label>
            <p class="detail-value">{{ $pwd->address}}</p>
        </div>
        
    </div>
    <div class="row">
        <div class="col-xl d-flex-xl d-flex">
            <label class="title-detail">Barangay : </label>
            <p class="detail-value">{{ $pwd->barangay->barangay_name}}</p>
        </div>
        <div class="col-xl d-flex-xl d-flex">
            <label class="title-detail">City : </label>
            <p class="detail-value">Cabuyao City</p>
        </div>
        <div class="col-xl d-flex-xl d-flex">
            <label class="title-detail">Province  : </label>
            <p class="detail-value">Laguna</p>
        </div>
        
    </div>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Phone No. : </label>
            <p class="detail-value">{{ $pwd->phone_number}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Telephone No. : </label>
            <p class="detail-value">{{ ($pwd->telephone_number == null)? "None" : $pwd->telephone_number}}</p>
        </div>
       
        <div class="col-xl d-flex">
            <label class="title-detail">Email Address : </label>
            <p class="detail-value">{{ ($pwd->email == null)? "None" :  $pwd->email}}</p>
           
        </div>
        
    </div>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Educational Attaintment : </label>
            <p class="detail-value">{{ $pwd->educational_attainment}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Status of Employment : </label>
            <p class="detail-value">{{ $pwd->employment_status}}</p>
           
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Category of Employment : </label>
            <p class="detail-value">{{ ($pwd->employment_category == null)? "None" :  $pwd->employment_category}}</p>
          
        </div>
    </div>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Type of Employment : </label>
            <p class="detail-value">{{ ($pwd->employment_type == null)? "None" :  $pwd->employment_type}}</p>
          
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Occupation : </label>
            <p class="detail-value">{{ ($pwd->occupation == null)? "None" :  $pwd->occupation}}</p>
      
        </div>    
    </div><br>
    <h5><b>Organization Information</b></h5><hr>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Organization Affiliated : </label>
            <p class="detail-value">{{ ($pwd->organization_affliated == null)? "None" :  $pwd->organization_affliated}}</p>
    
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Contact Person : </label>
            <p class="detail-value">{{ ($pwd->organization_contact_person == null)? "None" :  $pwd->organization_contact_person}}</p>
    
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Office Address : </label>
            <p class="detail-value">{{ ($pwd->organization_office_address == null)? "None" :  $pwd->organization_office_address}}</p>
      
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Tel. No.  : </label>
            <p class="detail-value">{{ ($pwd->organization_telephone_number == null)? "None" :  $pwd->organization_telephone_number}}</p>
        </div>
    </div><br>
    <h5><b>ID Reference No.</b></h5><hr>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">SSS No. : </label>
            <p class="detail-value">{{ ($pwd->sss_number == null)? "None" :  $pwd->sss_number}}</p>
    
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">GSIS No. : </label>
            <p class="detail-value">{{ ($pwd->gsis_number == null)? "None" :  $pwd->gsis_number}}</p>
    
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Pag-ibig No. : </label>
            <p class="detail-value">{{ ($pwd->pagibig_number == null)? "None" :  $pwd->pagibig_number}}</p>
         
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">PhilHealth No. : </label>
            <p class="detail-value">{{ ($pwd->philhealth_number == null)? "None" :  $pwd->philhealth_number}}</p>
        
        </div>
    </div><br>
    <h5><b>Family Background</b></h5><hr>
    <div class="row">
        <div class="col-xl d-flex">
            <label class="title-detail">Father's Name : </label>
            <p class="detail-value">{{ $pwd->father_first_name. " " . $pwd->father_middle_name ." ". $pwd->father_last_name}}</p>
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Mother's Name  : </label>
            <p class="detail-value">{{ $pwd->mother_first_name. " " . $pwd->mother_middle_name ." ". $pwd->mother_last_name}}</p>  
        </div>
        <div class="col-xl d-flex">
            <label class="title-detail">Guardian's Name  : </label>
            <p class="detail-value">{{ ($pwd->guardian_last_name == null)? "None" :  $pwd->guardian_first_name. " " . $pwd->guardian_middle_name ." ". $pwd->guardian_last_name}}</p>
        </div>
    </div>
    <div class="modal" id="documents">
        <div class="modal-dialog ">
            <div class="modal-content"  >
                <div class="modal-header">
                    <h4 class="modal-title">Upload Documents</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">              
                            <label class="text-danger m-auto">*Note : You can only upload pdf scan copy of documents </label>
                            <form action="{{ route('renewal.accept', $pwd) }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="row mt-2">
                                    <div class="col">
                                        <label class="title-detail">Medical Certification</label>
                                        <span class="text-danger">* Required</span>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="med_cert[]" accept="application/pdf" value="{{ old('images[]') }}" required multiple>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label class="title-detail">Authorization Letter</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="authorization[]" accept="application/pdf" multiple>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div> 
                                </div>         
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success buttons" value="Upload">
                </form>  
                </div>
            </div>
        </div>
    </div>    
</div>

<script>
    $(document).ready(function(){
    
        $(".custom-file-input").on("change", function() {
            var files = Array.from(this.files)
            var fileName = files.map(f =>{return f.name}).join(", ")
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        }); 
    });
    
</script>

@endsection