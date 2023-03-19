@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
<iframe id="frame" src="{{ asset('/id.html') }}" style="width: 0; height: 0; border: 0; border: none; position: absolute;"></iframe>
    <div class="container-fluid px-4">
        @if($transaction == 1)
            <h1><b>Application Transaction</b>
        @elseif($transaction == 2)
            <h1><b>Renewal of ID Transaction</b>
        @elseif($transaction == 3)
            <h1><b>Lost of ID Transaction</b>
        @else
            <h1><b>PWD Cancellation Transaction</b>
        @endif
        </h1><br>
        <div class="row">
            <div class="col-lg-6 mt-2">
                <h4><b>Personal Information</b></h4>
            </div>
            <div class="col-lg-6 text-right h6">
                <button id="generate-id" class="btn btn-primary">
                    <i class="fa fa-print" aria-hidden="true"></i>
                   <span class="ml-3">Generate ID</span> 
                </button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#upload-picture">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                    Upload ID Picture
                </button>
            </div>
        </div>
        <hr>
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
        <div class="modal" id="generate-id">
            <div class="modal-dialog ">
                <div class="modal-content"  >
                    <div class="modal-header">
                        <h4 class="modal-title">Generate Identification Card</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <a class="btn btn-success" id="accept-applicant" href="{{ route('add.pwd', $pwd) }}"><b>Generate ID</b></a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="text-danger" style="margin:auto;">Important : Once you click Generate ID the application of applicant is recorded.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="upload-picture">
            <div class="modal-dialog ">
                <div class="modal-content"  >
                    <div class="modal-header">
                        <h4 class="modal-title">ID Picture</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">                               
                                <form action="{{ route('upload.picture.id') }}" class="w-75 m-auto" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <input type="text" id="pwd" name="pwd" class="d-none">
                                    <input type="text" id="transaction" name="transaction" class="d-none">

                                    <label class="title-detail">Select Picture</label>    
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input " id="customFile" name="picture" accept="image/*" >
                                        <label class="custom-file-label" for="customFile">Select Picture</label>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-right">
                                <input type="submit" class="btn btn-success w-25" value="Upload">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                
                document.getElementById( "pwd" ).value = {!! json_encode($pwd->pwd_id)!!}
                document.getElementById( "transaction" ).value = {!! json_encode($transaction)!!}

                document.getElementById('frame').onload = function() {
                    document.getElementById("frame").contentDocument.getElementById("image-signatory").setAttribute("src", {!! json_encode($idDetails) !!});
                    if({!! json_encode(Session::get('hasID')) !!} == 1){
                        document.getElementById("frame").contentWindow.document.getElementById("picture-container").setAttribute("src", {!! json_encode($IDPic) !!});
                    }
                };
                $(".custom-file-input").on("change", function() {
                    var files = Array.from(this.files)
                    var fileName = files.map(f =>{return f.name}).join(", ")
                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });

                const today = new Date();
                $('#generate-id').click(function () {
                    document.getElementById("frame").contentWindow.document.getElementById("name").innerHTML = ""
                    document.getElementById("frame").contentWindow.document.getElementById("pwd-id").innerHTML = ""
                    document.getElementById("frame").contentWindow.document.getElementById("disability-type").innerHTML = ""
                    document.getElementById("frame").contentWindow.document.getElementById("address").innerHTML = "";
                    document.getElementById("frame").contentWindow.document.getElementById("birthday").innerHTML = "";
                    document.getElementById("frame").contentWindow.document.getElementById("date-issue").innerHTML = "";
                    document.getElementById("frame").contentWindow.document.getElementById("sex").innerHTML = ""
                    document.getElementById("frame").contentWindow.document.getElementById("blood-type").innerHTML = ""

                    document.getElementById("frame").contentWindow.document.getElementById("name").append({!! json_encode($pwd->first_name ." ". $pwd->middle_name ." ". $pwd->last_name) !!})
                    document.getElementById("frame").contentWindow.document.getElementById("pwd-id").append({!! json_encode($pwd->pwd_number) !!})
                    document.getElementById("frame").contentWindow.document.getElementById("disability-type").append({!! json_encode($pwd->disability_type) !!})
                    document.getElementById("frame").contentWindow.document.getElementById("address").append({!! json_encode($pwd->address . " Brgy. " . $pwd->barangay->barangay_name ." Cabuyao City Laguna") !!})
                    document.getElementById("frame").contentWindow.document.getElementById("birthday").append({!! json_encode(date('F d, Y', strtotime($pwd->birthday))) !!})
                    document.getElementById("frame").contentWindow.document.getElementById("date-issue").append({!! json_encode(date('F d, Y')) !!})
                    document.getElementById("frame").contentWindow.document.getElementById("sex").append({!! json_encode($pwd->sex) !!})
                    document.getElementById("frame").contentWindow.document.getElementById("blood-type").append({!! json_encode($pwd->blood_type) !!})

                    if({!! json_encode(Session::get('hasID')) !!} == null){
                        document.getElementById("frame").contentWindow.document.getElementById("picture-container").setAttribute("src", "");
                    }
                        
                    let wspFrame = document.getElementById('frame').contentWindow;
                    wspFrame.focus();
                    wspFrame.print();
                });
                

        
            });
        </script>


    

@endsection