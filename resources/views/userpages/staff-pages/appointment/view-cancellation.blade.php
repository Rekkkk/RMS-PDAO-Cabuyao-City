@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')
    <div class="container-fluid px-4">     
            <h2><b>Appointment for PWD Cancellation</b></h2>
            <span class="h4"><b>Date : </b>{{ date('F j, Y', strtotime($appointment->appointment_date )) }}</span>
            @if($appointment->appointment_status == "Pending" && $appointment->appointment_status != "Unprocess" && $appointment->appointment_date == date('Y-m-d') )
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <a class="btn btn-success buttons" id="btn-print" href="{{ route('accept.cancellation', [$pwd, $appointment] ) }}">Accept</a>
                        <input type="button" value=" Decline "data-toggle="modal" data-target="#decline-appointment" class="btn btn-danger buttons">
                    </div>
                </div>
            @elseif($appointment->appointment_status == "Done")
                <div class="row">
                    <div class="col-lg-12 ">
                        <p class="h4"><b>Status: </b><span class="h4 text-success">{{ $appointment->appointment_status}}</span></p>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 ">
                        <p class="h4"><b>Status: </b><span class="h4 text-danger">{{ $appointment->appointment_status}}</span></p>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="mt-3"><b>Personal Information</b></h3>
                </div>
                @if($appointment->appointment_status == "Pending" || $appointment->appointment_status == "Decline")
                <div class="col-lg-6 mt-1 text-right">
                    <input type="button" value="Remarks" class="btn btn-link" data-toggle="modal" data-target="#appointment-remarks">
                    @if($appointment->appointment_status == "Pending" && ($appointment->appointment_date == date('Y-m-d')))
                    <input type="button" value="Upload Documents" class="btn btn-link" data-toggle="modal" data-target="#appointment-documents">
                    @endif
                </div>
                @endif
            </div><hr>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail" >PWD Number : </label>
                    <p class="detail-value">{{ $pwd->pwd_number}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail" >Last Name : </label>
                    <p class="detail-value">{{ $pwd->last_name}}</p>
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
                    <p class="detail-value">{{ $pwd->suffix}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Age : </label>
                    <p class="detail-value">{{ $pwd->age}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Date of Birth :</label>
                    <p class="detail-value">{{ date('F d, Y', strtotime($pwd->birthday))}}</p>
          
                </div>       
                
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Religion : </label>
                    <p class="detail-value">{{ $pwd->religion}}</p>

                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Ethnic Group : </label>
                    <p class="detail-value">{{ $pwd->ethnic_group}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Sex : </label>
                    <p class="detail-value">{{ $pwd->sex}}</p>
                </div>
               
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Civil Status : </label>
                    <p class="detail-value">{{ $pwd->civil_status}}</p>
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
                    <label class="title-detail">Disablity Name : </label>
                    <p class="detail-value">{{ $pwd->disability_name}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Address : </label>
                    <p class="detail-value">{{ $pwd->address}}</p>
                </div>
                
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Barangay : </label>
                    <p class="detail-value">{{ $pwd->barangay->barangay_name}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">City : </label>
                    <p class="detail-value">Cabuyao City</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Province : </label>
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
                    <p class="detail-value">{{ $pwd->telephone_number}}</p>
                </div>
               
                <div class="col-xl d-flex">
                    <label class="title-detail">Email Address : </label>
                    <p class="detail-value">{{ $pwd->email}}</p>
                   
                </div>
               
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Educational Attaintment: </label>
                    <p class="detail-value">{{ $pwd->educational_attainment}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Status of Employment : </label>
                    <p class="detail-value">{{ $pwd->employment_status}}</p>
                   
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Category of Employment : </label>
                    <p class="detail-value">{{ $pwd->employment_category}}</p>
                  
                </div>
                
            </div>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Type of Employment : </label>
                    <p class="detail-value">{{ $pwd->employment_type}}</p>
                  
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Occupation : </label>
                    <p class="detail-value">{{ $pwd->occupation}}</p>
              
                </div>
            </div><br>
            <h5><b>Organization Information</b></h5><hr>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Organization Affiliated : </label>
                    <p class="detail-value">{{ $pwd->organization_affliated}}</p>
        
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Contact Person : </label>
                    <p class="detail-value">{{ $pwd->organization_contact_person}}</p>

                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Office Address : </label>
                    <p class="detail-value">{{ $pwd->organization_office_address}}</p>
              
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Tel. No. : </label>
                    <p class="detail-value">{{ $pwd->organization_telephone_number}}</p>
                </div>
            </div><br>
            <h5><b>ID Reference No.</b></h5><hr>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">SSS No. : </label>
                    <p class="detail-value">{{ $pwd->sss_number}}</p>
          
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">GSIS No. : </label>
                    <p class="detail-value">{{ $pwd->gsis_number}}</p>

                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Pag-ibig No. : </label>
                    <p class="detail-value">{{ $pwd->pagibig_number}}</p>
                 
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">PhilHealth No. : </label>
                    <p class="detail-value">{{ $pwd->philhealth_number}}</p>
                
                </div>
            </div><br>
            <h5><b>Family Background</b></h5><hr>
            <div class="row">
                <div class="col-xl d-flex">
                    <label class="title-detail">Father's Name : </label>
                    <p class="detail-value">{{ $pwd->father_first_name. " " . $pwd->father_middle_name ." ". $pwd->father_last_name}}</p>
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Mother's Name : </label>
                    <p class="detail-value">{{ $pwd->mother_first_name. " " . $pwd->mother_middle_name ." ". $pwd->mother_last_name}}</p>  
                </div>
                <div class="col-xl d-flex">
                    <label class="title-detail">Guardian's Name : </label>
                    <p class="detail-value">{{ $pwd->guardian_first_name. " " . $pwd->guardian_middle_name ." ". $pwd->guardian_last_name}}</p>
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
                                        <label>Re Appointment Date (Optional) :</label>
                                        <input type="text" class="form-control" id="datepicker-appointment" name="appointment_date" placeholder="Click to choose date" readonly="false" required>
                                        @if ($errors->has('appointment_date'))
                                            <span class="text-danger">{{ $errors->first('appointment_date') }}</span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="Submit">
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
                                <label class="title-detail"><b>PWD ID :</b></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach($appointment->pictures as $pictures)
                                            @if($pictures->docs_type == "PWD ID")
                                                <div class="d-flex mt-1">
                                                    <a href="{{ route('delete.docs', [$pictures->img_id, $appointment]) }}" class="btn btn-danger mr-1" style="height: 27px; font-size:10px" title="Delete" > <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    <a class="mt-1" href="{{ asset('/images/'.$pictures->img_name) }}" target="_blank">{{$pictures->img_name}}</a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @if($appointment->pictures->where('docs_type', 'Authorization')->count() != 0)
                                    <br><label class="title-detail"><b>Authorization Letter :</b></label>
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
                                    </div>     
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
                        <div class="row">
                            <div class="col-lg-12">        
                            <label class="text-danger m-auto">*Note : You can only upload pdf scan copy of documents </label>
                                <form action="{{ route('appointment.docs', $appointment) }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <div class="row mt-2">
                                        <div class="col">
                                            <label class="title-detail">PWD ID</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile" name="Id[]" accept="application/pdf" multiple >
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
        jQuery(document).ready(function () { 

            $(".custom-file-input").on("change", function() {
                var files = Array.from(this.files)
                var fileName = files.map(f =>{return f.name}).join(", ")
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });
    
    
    </script>
@endsection