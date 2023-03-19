@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
    <div class="container-fluid px-4" >  
            <h1><b>Appointment for New Applicant </b></h1>
            <span class="h4"><b>Date : </b>{{ date('F j, Y', strtotime($appointment->appointment_date )) }}</span>
                @if($appointment->appointment_status == "Pending" && $appointment->appointment_status != "Unprocess" && $appointment->appointment_date == date('Y-m-d') )
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <a href="{{ route('accept.applicant', $appointment->appointment_id) }}" class="btn btn-success buttons">Accept</a>
                            <input type="button" value=" Decline "data-toggle="modal" data-target="#decline-appointment" class="btn btn-danger buttons">
                        </div>
                    </div>
                @elseif($appointment->appointment_status == "Done")
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="h4"><b>Status: </b><span class="h4 text-success">{{ $appointment->appointment_status}}</span></p>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="h4"><b>Status: </b><span class="h4 text-danger">{{ $appointment->appointment_status}}</span></p>
                        </div>
                    </div>
                @endif
            {{-- @endif --}}
            <div class="row mt-2">
                <div class="col-lg-6">
                    <h5 class="mt-3"><b>Personal Information</b></h5>
                </div>
                
                @if($appointment->appointment_status == "Pending" || $appointment->appointment_status == "Decline")
                    <div class="col-lg-6 mt-1 text-right">
                        <input type="button" value="Remarks" class="btn btn-link" data-toggle="modal" data-target="#appointment-remarks">
                        @if($appointment->appointment_status == "Pending" && ($appointment->appointment_date == date('Y-m-d')))
                            <input type="button" value="Upload Documents" class="btn btn-link" data-toggle="modal" data-target="#appointment-documents">
                        @endif
                    </div>
                @endif
            </div>
            <hr>
            <div class="row">
                <div class="col-xl d-flex">  
                    <label class="title-detail">Last Name : </label>
                    <p class="detail-value">{{ $appointment->applicant->last_name}}  </p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">First name : </label>
                    <p class="detail-value">{{ $appointment->applicant->first_name}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Middle Name : </label>
                    <p class="detail-value">{{ $appointment->applicant->middle_name}}</p>
                </div>
                
            </div>

            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Suffix : </label>
                    <p class="detail-value">{{ ($appointment->applicant->suffix == null)? "None" : $appointment->applicant->suffix}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Sex : </label>
                    <p class="detail-value">{{ $appointment->applicant->sex}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Civil Status : </label>
                    <p class="detail-value">{{ $appointment->applicant->civil_status}}</p>
                </div>
               
                
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Age : </label>
                    <p class="detail-value">{{ $appointment->applicant->age}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Date of Birth : </label>
                    <p class="detail-value">{{date('F d, Y', strtotime($appointment->applicant->birthday))}}</p>
          
                </div>       
                <div class="col-xl d-flex">
                    <label class="title-detail">Religion : </label>
                    <p class="detail-value">{{ $appointment->applicant->religion}}</p>
                </div>
               
               
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Ethnic Group : </label>
                    <p class="detail-value">{{ ($appointment->applicant->ethnic_group == null)? "None" : $appointment->applicant->ethnic_group}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Blood Type : </label>
                    <p class="detail-value">{{ $appointment->applicant->blood_type}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Type of Disability : </label>
                    <p class="detail-value">{{ $appointment->applicant->disability_type}}</p>
                 
                </div>
                
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Cause of Disablity : </label>
                    <p class="detail-value">{{ $appointment->applicant->disability_cause}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Cause of Disablity : </label>
                    <p class="detail-value">{{ $appointment->applicant->disability_name}}</p>
                </div>
                <div class="col-xl d-flex-xl d-flex">
                    <label class="title-detail">Address : </label>
                    <p class="detail-value">{{ $appointment->applicant->address}}</p>
                </div>
                
            </div>
            <div class="row">
                <div class="col-xl d-flex-xl d-flex">
                    <label class="title-detail">Barangay : </label>
                    <p class="detail-value">{{ $appointment->applicant->barangay->barangay_name}}</p>
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
                    <p class="detail-value">{{ $appointment->applicant->phone_number}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Telephone No. : </label>
                    <p class="detail-value">{{ ($appointment->applicant->telephone_number == null)? "None" : $appointment->applicant->telephone_number}}</p>
                </div>
               
                <div class="col-xl d-flex">
                    <label class="title-detail">Email Address : </label>
                    <p class="detail-value">{{ ($appointment->applicant->email == null)? "None" :  $appointment->applicant->email}}</p>
                   
                </div>
                
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Educational Attaintment : </label>
                    <p class="detail-value">{{ $appointment->applicant->educational_attainment}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Status of Employment : </label>
                    <p class="detail-value">{{ $appointment->applicant->employment_status}}</p>
                   
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Category of Employment : </label>
                    <p class="detail-value">{{ ($appointment->applicant->employment_category == null)? "None" :  $appointment->applicant->employment_category}}</p>
                  
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 d-flex">
                    <label class="title-detail">Type of Employment : </label>
                    <p class="detail-value">{{ ($appointment->applicant->employment_type == null)? "None" :  $appointment->applicant->employment_type}}</p>
                  
                </div>
                <div class="col-xl-4 d-flex">
                    <label class="title-detail">Occupation : </label>
                    <p class="detail-value">{{ ($appointment->applicant->occupation == null)? "None" :  $appointment->applicant->occupation}}</p>
              
                </div>    
            </div><br>
            <h5><b>Organization Information</b></h5><hr>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Organization Affiliated : </label>
                    <p class="detail-value">{{ ($appointment->applicant->organization_affliated == null)? "None" :  $appointment->applicant->organization_affliated}}</p>
        
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Contact Person : </label>
                    <p class="detail-value">{{ ($appointment->applicant->organization_contact_person == null)? "None" :  $appointment->applicant->organization_contact_person}}</p>

                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Office Address : </label>
                    <p class="detail-value">{{ ($appointment->applicant->organization_office_address == null)? "None" :  $appointment->applicant->organization_office_address}}</p>
              
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Tel. No.  : </label>
                    <p class="detail-value">{{ ($appointment->applicant->organization_telephone_number == null)? "None" :  $appointment->applicant->organization_telephone_number}}</p>
                </div>
            </div><br>
            <h5><b>ID Reference No.</b></h5><hr>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">SSS No. : </label>
                    <p class="detail-value">{{ ($appointment->applicant->sss_number == null)? "None" :  $appointment->applicant->sss_number}}</p>
          
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">GSIS No. : </label>
                    <p class="detail-value">{{ ($appointment->applicant->gsis_number == null)? "None" :  $appointment->applicant->gsis_number}}</p>

                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Pag-ibig No. : </label>
                    <p class="detail-value">{{ ($appointment->applicant->pagibig_number == null)? "None" :  $appointment->applicant->pagibig_number}}</p>
                 
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">PhilHealth No. : </label>
                    <p class="detail-value">{{ ($appointment->applicant->philhealth_number == null)? "None" :  $appointment->applicant->philhealth_number}}</p>
                
                </div>
            </div><br>
            <h5><b>Family Background</b></h5><hr>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Father's Name : </label>
                    <p class="detail-value">{{ $appointment->applicant->father_first_name. " " . $appointment->applicant->father_middle_name ." ". $appointment->applicant->father_last_name}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Mother's Name  : </label>
                    <p class="detail-value">{{ $appointment->applicant->mother_first_name. " " . $appointment->applicant->mother_middle_name ." ". $appointment->applicant->mother_last_name}}</p>  
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Guardian's Name  : </label>
                    <p class="detail-value">{{ ($appointment->applicant->guardian_last_name == null)? "None" :  $appointment->applicant->guardian_first_name. " " . $appointment->applicant->guardian_middle_name ." ". $appointment->applicant->guardian_last_name}}</p>
                </div>
            </div>

            <div class="modal" id="decline-appointment">
                <div class="modal-dialog ">
                    <div class="modal-content"  >
                        <div class="modal-header">
                            <h4 class="modal-title">Decline Appointment</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <form action="{{ route('re-appointment',$appointment->appointment_id) }}" method="POST">
                                        @csrf
                                        <label class="title-detail">Remarks :</label>
                                        <textarea name="remarks" class="form-control"  cols="10" rows="5"></textarea><br>
                                        <div class="d-flex"> 
                                        </div>
                                        <label class="title-detail">Re Appointment Date (Optional) :</label>
                                        <input type="text" class="form-control" id="datepicker-appointment" name="appointment_date" placeholder="Click to choose date" readonly="false" required>
                                        @if ($errors->has('appointment_date'))
                                        <span class="text-danger">{{ $errors->first('appointment_date') }}</span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success b" value="Confirm">
                        </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="appointment-remarks">
                <div class="modal-dialog ">
                    <div class="modal-content"  >
                        <div class="modal-header">
                            <h4 class="modal-title">Appointment Remarks</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">                               
                                    <label class="h5"><b>Remarks</b></label>
                                    <p class="mt-1 h6">{{ $remark }}</p>                  
                                </div>
                            </div><hr>
                            <label class="h5"><b>Client Documents</b></label><br>
                            @if($appointment->pictures->count() != 0)
                                <label class="title-detail"><b>Medical Certificate</b></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach($appointment->pictures as $pictures)
                                            @if($pictures->docs_type == "Medical Certificate")
                                            
                                                <div class="d-flex mt-1">
                                                    <a href="{{ route('delete.docs', [$pictures->img_id, $appointment]) }}" class="btn btn-danger mr-1" style="height: 27px; font-size:10px" title="Delete" > <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    <a class="mt-1" href="{{ asset('/images/'.$pictures->img_name) }}" target="_blank">{{$pictures->img_name}}</a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <br> 
                                <label class="title-detail "><b>Proof of Voters</b></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach($appointment->pictures as $pictures)
                                            @if($pictures->docs_type == "Voters Confirmation")
                                                <div class="d-flex mt-1">
                                                    <a href="{{ route('delete.docs', [$pictures->img_id, $appointment]) }}" class="btn btn-danger mr-1" style="height: 27px; font-size:10px" title="Delete" > <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    <a class="mt-1" href="{{ asset('/images/'.$pictures->img_name) }}" target="_blank">{{$pictures->img_name}}</a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @if($appointment->pictures->where('docs_type', 'Authorization')->count() != 0)
                                    <br><label class="title-detail"><b>Authorization Letter</b></label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @foreach($appointment->pictures as $pictures)
                                                @if($pictures->docs_type == "Authorization")
                                                    <div class="d-flex mt-1">
                                                        <a href="{{ route('delete.docs', [$pictures->img_id, $appointment]) }}" class="btn btn-danger mr-1" style="height: 27px; font-size:10px" title="Delete" > <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                        <a class="mt-1" href="{{ asset('/images/'.$pictures->img_name) }}" target="_blank">{{$pictures->img_name}}</a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div><br>
                                @endif
                            @else
                                <p class="mt-1 h6">No Uploaded Documents</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="appointment-documents">
                <div class="modal-dialog ">
                    <div class="modal-content"  >
                        <div class="modal-header">
                            <h4 class="modal-title">Appointment Documents</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                             <label class="text-danger m-auto">*Note : You can only upload pdf scan copy of documents </label>

                            <form action="{{ route('appointment.docs', $appointment) }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="row mt-2">
                                    <div class="col">
                                        <label class="title-detail">Medical Certification</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="med_cert[]" accept="application/pdf" value="{{ old('images[]') }}" multiple>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label class="title-detail">Voter's ID or Certification</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="voters[]" accept="application/pdf" multiple >
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
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success buttons" value="Upload">
                        </form>  
                        </div>
                    </div>   
                </div>
            </div>

    </div><br><br>
<script>
    $(document).ready(function(){
        $("img").click( function() {
                this.requestFullscreen();
        });
        $(".custom-file-input").on("change", function() {
            var files = Array.from(this.files)
            var fileName = files.map(f =>{return f.name}).join(", ")
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>
@endsection