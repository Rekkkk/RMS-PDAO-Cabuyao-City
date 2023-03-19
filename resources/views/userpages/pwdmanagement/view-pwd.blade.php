@extends('userpages.sidebar')

@section('content')
@include('sweetalert::alert')

<a href="{{ route('pwd.management') }}" class="btn btn-primary buttons mb-3">Back</a><br><br>   

    <div class="container-fluid px-4">
        <div class="row ">
            <div class="col-lg-6">
                <h5><b>PWD Number :</b> {{$pwd->pwd_number}}</h5>
                <div class="d-flex">
                    <h5 class=""><b>PWD Status : </b></h5>
                    @if ($pwd->pwd_status->id_expiration > date("Y-m-d") && $pwd->pwd_status->cancelled == 0)
                        <h5 class="text-success ml-1"> Active</h5>
                    @elseif($pwd->pwd_status->id_expiration < date("Y-m-d") && $pwd->pwd_status->cancelled == 0)
                        <h5 class="text-danger ml-1"> Inactive</h5>
                    @else
                        <h5 class="text-danger ml-1"> Cancelled</h5>
                    @endif
                
                </div>
            </div>
            <div class="col-lg-6 text-right">
                @if(Auth::user()->user_role == 1 || Auth::user()->user_role == 2 )
                    <a href="{{route('print.pwd', $pwd) }}" class="btn btn-info" title="Print PWD details">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </a>
                @endif
                @if(Auth::user()->user_role == 0 && $pwd->pwd_status->cancelled == 0)
                <div class="dropdown dropleft float-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Select Transaction
                    </button>
                    <div class="dropdown-menu text-center">
                        @if($pwd->pwd_status->id_expiration < date('Y-m-d'))
                        <a class="dropdown-item" href="{{ route('renewal.pwd', $pwd ) }}" >
                            RENEWAL ID
                        </a>
                        @else
                        <a class="dropdown-item" href="{{ route('lostid.pwd', $pwd) }}" >
                            LOST ID 
                        </a>   
                        @endif
                        <a class="dropdown-item" href="{{ route('cancellation.pwd', $pwd ) }}">
                            CANCELLATION 
                        </a>
                    </div> 
                </div>         
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <h4><b>Personal Information</b>
                    @if(Auth::user()->user_role == 0)
                        <a href="{{ route('update.pwd', $pwd->pwd_id) }}" title="Edit PWD Details" class="btn btn-info ml-1">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                    @endif
                </h4>
            </div>
            @if(Auth::user()->user_role != 0)
            <div class="col-lg-6 text-right h6">
                <a href="{{ route('pwd.docs', $pwd) }}">View Pwd Documents </a>
            </div>
            @endif
            
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
                <p class="detail-value">{{ ($pwd->middle_name == null)? "None" : $pwd->middle_name}}</p>
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
                <label class="title-detail">Disablity Name : </label>
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
                <p class="detail-value">{{ ($pwd->guardian_last_name == null)? "None" :  $pwd->guardian_first_name. " " . $pwd->guardian_middle_name ." ". $pwd->guardian_last_name }}</p>
            </div>
        </div>

@endsection