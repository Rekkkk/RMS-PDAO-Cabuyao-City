<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\NewApplicant;
use App\Models\Renewal;
use App\Models\LostId;
use App\Models\Cancellation;
use App\Models\Pwd;
use App\Models\Barangay;
use App\Models\AppointmentLimit;
use App\Models\AppointmentDayDisable;
use Illuminate\Support\Facades\Auth;
use App\Models\PwdStatus;
use App\Models\AppointmentRemarks;
use App\Models\AppointmentDocs;
use App\Models\PwdDocs;
use App\Models\Signatory;
use RealRashid\SweetAlert\Facades\Alert;
use Dompdf\Dompdf;
use DB;
use Validator;
use Carbon\Carbon;
use App\Models\ActivityLog;
use Session;
use Illuminate\Support\Facades\File;
use Storage;

class AppointmentController extends Controller
{
    public function checkAvaildate($date){

        $totalAppointment = Appointment::where('appointment_date', $date)->count();
        $limit = AppointmentLimit::where('appointment_month', date('Y-m', strtotime($date)))->get()->first();

        if($totalAppointment == $limit->limits){
            return true;
        }
    }
    
    public function printReferenceNumber(Appointment $appointment){

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/report-template/appointment-template', compact('appointment'));
    
        $pdf->render();

        return $pdf->download($appointment->transaction.'.pdf');
       
    }

    public function appointmentApplicant(){

        $appointment = Appointment::all()->first();

        return view(' landingpage/landingpages/appointments/application-form', compact('appointment'));
    }

    public function appointmentPage(){
        
        $barangays = Barangay::all();

        $appointment = Appointment::where('appointment_status', 'Pending')
        ->where('appointment_date', date("Y-m-d"))
        ->get();

        $allAppointment = Appointment::where('appointment_date', date("Y-m-d"))
        ->get();

        $appointmentProcessed =  Appointment::where('appointment_status','!=' ,'Pending')
        ->where('appointment_date', date("Y-m-d"))
        ->count();

        return view('userpages/staff-pages/appointment/appointment-management', compact('appointment', 'allAppointment', 'appointmentProcessed', 'barangays'));

    }  

    public function viewAllAppointment(){

        $appointment = Appointment::all();
          
        return view('userpages/staff-pages/appointment/all-appointment', compact('appointment'));

    }  

    public function selectAppointment($id){

        $appointment = Appointment::where('appointment_id', $id)->first();

        if($appointment->transaction == 'Application'){

            $pwd =  DB::table('pwd')->count();

            if($pwd == 0){
                $pwd = 1;
            }

            if($appointment->remark){

                $remark = $appointment->remark->remark;

            }else{

                $remark = 'None';

            }

            return view('userpages/staff-pages/appointment/view-applicant-appointment', compact('appointment', 'remark', 'pwd'));

        }
        else if($appointment->transaction == 'Renewal ID'){

            $pwd = Pwd::findOrFail($appointment->renewal->pwd_id);

            if($appointment->remark)
                $remark = $appointment->remark->remark;
            else
                $remark = 'None';

            return view('userpages/staff-pages/appointment/view-renewal-appointment', compact('pwd', 'appointment', 'remark'));
        }
        elseif($appointment->transaction == 'Lost ID'){
            
            $pwd = Pwd::findOrFail($appointment->lostId->pwd_id);

            if($appointment->remark){

                $remark = $appointment->remark->remark;

            }else{
                $remark = 'None';
            }

            return view('userpages/staff-pages/appointment/view-lost-id-appointment', compact('pwd', 'appointment', 'remark'));
        }
        else{

            $pwd = Pwd::findOrFail($appointment->cancellation->pwd_id);

            if($appointment->remark){

                $remark = $appointment->remark->remark;

            }else{
                $remark = 'None';
            }

            return view('userpages/staff-pages/appointment/view-cancellation', compact('pwd', 'appointment', 'remark'));
        }
    }

    public function generateIdPage(Pwd $pwd, $transaction){
        
        $idFile = Signatory::where('signatory_type', 'ID')->first()->img_file;
        
        $idDetails = "signatory/".$idFile;
        
        if(Session::get('hasID') == 1){
            $idDetailsPic = Signatory::where('signatory_type', 'IDPIC')->first()->img_file;
            
            $IDPic = "signatory/".$idDetailsPic;
            
        }else{
            $IDPic = null;
        }

        return view('userpages/staff-pages/generate-id-page', compact('pwd', 'transaction', 'idDetails', 'IDPic'));

    }

    public function reAppointment(Request $request, $appointment){

        $validator = Validator::make($request->all(), [
            'remarks' => 'required',
        ]);

        if ($validator->fails()) {
            Alert::html('ERROR', 'Remarks Message is Required !', 'error');
            
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $selectedAppointment = Appointment::find($appointment);

        $ifExistRemark = AppointmentRemarks::where('appointment_id', $appointment)->first();

        $appointmentTransaction;

        if($selectedAppointment == "Application"){
            $appointmentTransaction = "APL";
        }elseif($selectedAppointment == "Renewal ID"){
            $appointmentTransaction = "RNW";
        }elseif($selectedAppointment == "Lost ID"){
            $appointmentTransaction = "LST";
        }else{
            $appointmentTransaction = "RCL"; 
        }

        if($ifExistRemark){
            if($request->appointment_date == null){

                AppointmentRemarks::where('appointment_id', $appointment)
                                    ->update(['remark' => $request->remarks]);

                $selectedAppointment->is_reschedule = 0 ;
                $selectedAppointment->appointment_status = 'Decline' ;
                $selectedAppointment->save();

                ActivityLog::create([
                    'user_id' => Auth::user()->user_id,
                    'eventdetails' => "User decline an scheduled transaction for Lost ID  . Reference no. " .  $appointmentTransaction . date('j', strtotime($selectedAppointment->appointment_date ))."-0". $selectedAppointment->barangay_id."-".$selectedAppointment->appointment_id."0"
                ]);    

                Alert::success('Success', ' Decline transaction success !');

                return back();
            }else{

                AppointmentRemarks::where('appointment_id', $appointment)
                                    ->update(['remark' => $request->remarks]);
                                    
                $selectedAppointment->appointment_date =  $request->appointment_date;

                $selectedAppointment->save();
                Alert::success('Success', 'Re-Appointment Success !');

                ActivityLog::create([
                    'user_id' => Auth::user()->user_id,
                    'eventdetails' => "User re-appointment an scheduled transaction for Lost ID  . Reference no. " .  $appointmentTransaction . date('j', strtotime($selectedAppointment->appointment_date ))."-0". $selectedAppointment->barangay_id."-".$selectedAppointment->appointment_id."0"
                ]);    


                return redirect()->route('appointment.page');
            }

        }else{
            if($request->appointment_date == null){
                AppointmentRemarks::create([
                    'appointment_id' => $selectedAppointment->appointment_id,
                    'remark' => $request->remarks
                ]);

                $selectedAppointment->is_reschedule = 0 ;
                
                $selectedAppointment->appointment_status = 'Decline' ;
                $selectedAppointment->save();
                Alert::success('Success', ' Decline transaction success !');

                
                ActivityLog::create([
                    'user_id' => Auth::user()->user_id,
                    'eventdetails' => "User decline an scheduled transaction for Lost ID  . Reference no. " . $appointmentTransaction . date('j', strtotime($selectedAppointment->appointment_date ))."-0". $selectedAppointment->barangay_id."-".$selectedAppointment->appointment_id."0"
                ]);   

                return back();
            }else{

                AppointmentRemarks::create([
                    'appointment_id' => $selectedAppointment->appointment_id,
                    'remark' => $request->remarks
                ]);
                $selectedAppointment->appointment_date =  $request->appointment_date;
                $selectedAppointment->save();

                ActivityLog::create([
                    'user_id' => Auth::user()->user_id,
                    'eventdetails' => "User re-appointment an scheduled transaction for Lost ID  . Reference no. " . $appointmentTransaction . date('j', strtotime($selectedAppointment->appointment_date ))."-0". $selectedAppointment->barangay_id."-".$selectedAppointment->appointment_id."0"
                ]); 

                Alert::success('Success', 'Re-Appointment Success !');
                
                return redirect()->route('appointment.page');
            }

        }

    }

    public function deleteDocs($imageId, Appointment $appointment ){

        $selectDocs = AppointmentDocs::where('img_id', $imageId)->first();

        $filename = public_path("/images/$selectDocs->img_name");

        if(File::exists(  $filename)) {
            File::delete($filename);
            $selectDocs->delete();
        }

        $eventDetailed;

        if($appointment->transaction == "Application")
            $eventDetailed = "APL" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";
        elseif($appointment->transaction == "Renewal ID")
            $eventDetailed = "RNW" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";
        elseif($appointment->transaction == "Lost ID")
            $eventDetailed = "LST" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";
        else
            $eventDetailed = "RCL" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User upload document from scheduled transaction for " . $appointment->transaction . " Reference no. " .  $eventDetailed
        ]);   

        Alert::success('Success', 'Document Deleted !');
        return back();

    }

    public function updloadAppointmentDocs(Request $request, Appointment $appointment){

        if($request->med_cert != null) {
            foreach (($request->med_cert) as $imagefile) {
                $image = new AppointmentDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Medical Certificate";
                $image->appointment = $appointment->transaction;
                $image->appointment_id = $appointment->appointment_id;
                
                $image->save();
            }
        }
        
        if($request->voters != null) {
            foreach (($request->voters) as $imagefile) {
                $image = new AppointmentDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Voters Confirmation";
                $image->appointment = $appointment->transaction;
                $image->appointment_id = $appointment->appointment_id;
                
                $image->save();
            }
        }

        if($request->authorization != null) {
            foreach (($request->authorization) as $imagefile) {
                $image = new AppointmentDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Authorization";
                $image->appointment = $appointment->transaction;
                $image->appointment_id = $appointment->appointment_id;
                
                $image->save();
            }
        }

        if($request->affidavit != null) {
            foreach (($request->affidavit) as $imagefile) {
                $image = new AppointmentDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Affidavit Of Lost";
                $image->appointment = $appointment->transaction;
                $image->appointment_id = $appointment->appointment_id;
                
                $image->save();
            }
        }
        
        if($request->Id != null) {
            foreach (($request->Id) as $imagefile) {
                $image = new AppointmentDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "PWD ID";
                $image->appointment = $appointment->transaction;
                $image->appointment_id = $appointment->appointment_id;
                
                $image->save();
            }
        }
        
        if($request->authorization != null || $request->voters != null || $request->med_cert != null || $request->affidavit != null || $request->id != null){

            $eventDetailed;

            if($appointment->transaction == "Application")
                $eventDetailed = "APL" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";
            elseif($appointment->transaction == "Renewal ID")
                $eventDetailed = "RNW" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";
            elseif($appointment->transaction == "Lost ID")
                $eventDetailed = "LST" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";
            else
                $eventDetailed = "RCL" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0";
    
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User upload document from scheduled transaction for " . $appointment->transaction . " Reference no. " .  $eventDetailed
            ]);   
    
            Alert::success('Success', 'Document Uploaded Successfully !');
    
            return back();
        }
        else{
            return back();
        }
    }

    public function limitDateAppointment(Request $request){

        $request->validate([
            'appointment_month' => 'required',
            'limits' => 'required',
        ]);

        $condition = true; 

        $lastDayofMonth = Carbon::now()->endOfMonth()->toDateString();

       
        if(date('Y-m', strtotime($request->appointment_month)) == date('Y-m')){

            Alert::html('Error', 'You cannot limit number of appointments in current month !', 'error');
            return back();
        }
        
        if(date('Y-m', strtotime($request->appointment_month)) > date('Y-m')){
            $condition = false; 
        }

        if($lastDayofMonth <= Carbon::now()->addDays(14)->toDateString() &&  $condition == true){

            Alert::html('Error', 'You cannot set limit number of transaction in selected month before last 14 days of the month !', 'error');
            return back();

        }

        $ifMonthExist = DB::table('appointment_limit_months')->where('appointment_month', $request->appointment_month)->first();

        if($ifMonthExist){
            Alert::html('Error', 'Appointment Limit for this month set already !', 'error');
            return back();
        }else{
            AppointmentLimit::create([
                'appointment_month' => $request->appointment_month,
                'limits' => $request->limits
            ]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User set an limit of appointments for month of " . date('F Y', strtotime($request->appointment_month))
            ]); 

            Alert::success('Success', 'Appointment Limit Set Successfully !');
            return back();
        }

    }

    public function editLimitDateAppointment(Request $request){

        if($request->date == Carbon::now()->format('Y-m')){
            Alert::html('Error', 'You cannot limit number of appointments in current month !', 'error');
            return back();
        }

        if(Carbon::createFromFormat('Y-m', $request->date)->format('Y-m') > Carbon::now()->addMonth(1)->format('Y-m')){
            AppointmentLimit::where('appointment_month', $request->date)->first()
                                ->update(['limits' => $request->limit]);
    
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User edit a limit of appointments for month of " . date('F Y', strtotime($request->date))
            ]); 
    
                Alert::success('Success', 'Appointment Limit Updated Successfully !');
                return back(); 
        }
        if(Carbon::now()->addMonth(1)->format('Y-m') == $request->date){

            if(Carbon::now()->endOfMonth()->toDateString() <= Carbon::now()->addDays(14)->toDateString()){
                Alert::html('Error', 'You cannot edit limit number of appointments in selected month after last 14 days of the month !', 'error');
                return back(); 
            }else{
                AppointmentLimit::where('appointment_month', $request->date)->first()
                ->update(['limits' => $request->limit]);
    
                ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User edit a limit of appointments for month of " . date('F Y', strtotime($request->date))
                ]); 
    
                Alert::success('Success', 'Appointment Limit Updated Successfully !');
                return back(); 
            }
        }else{
            Alert::html('Error', 'Appointment month edit unsucessfully !', 'error');
            return back(); 
        }         
        
    }

    public function disableDate(Request $request){

        $dateDisable = DB::table('appointment_disable_day')->where('date', $request->date)->first();

        if($dateDisable){
            Alert::html('Error', 'This date are disabled already !', 'error');
            return back();
        }else{
            $request->validate([
                'date' => 'required',
            ]);
    
            AppointmentDayDisable::create([
                'date' => $request->date,
            ]);
    
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User disabled " . date('F d, Y', strtotime($request->date)) . " for appointment date"
            ]); 

            Alert::success('Success', 'Appointment Date Disabled Successfully !');
            return back();
            
        }
        
    }

    public function removedisableDate($date){

        DB::table('appointment_disable_day')->where('date', $date)->delete();

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User remove " . date('F d, Y', strtotime($date)) . " as disabled appointment date"
        ]); 

        Alert::success('Success', 'Appointment date disabled remove successfully !');

        return back();
    }

    public function newApplicantCreate(Request $request){
    
        $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'birthday' => 'required',
            'religion' => 'required',
            'sex' => 'required',
            'educational_attainment' => 'required',
            'employment_status' => 'required',
            'employment_category' => 'required',
            'employment_type' => 'required',
            'occupation' => 'required',
            'civil_status' => 'required',
            'blood_type' => 'required',
            'disability_type' => 'required',
            'disability_cause' => 'required',
            'disability_name' => 'required',
            'address' => 'required',
            'barangay_id' => 'required',
            'phone_number'  => 'required',
            'father_last_name' => 'required',
            'father_first_name' => 'required',
            'mother_last_name' => 'required',
            'mother_first_name' => 'required',
        ]);
        
        if($this->checkAvaildate($request->appointment_date)){

            Alert::html('Error', 'Selected Date not availble', 'error');

            return back()->withInput();

        }

        if(Pwd::where('email', $request->email)->first())
        {
            Alert::html('Error, Email Register already', 'error');
            return back()->withInput();
        }

        $AllApplication =  Appointment::where('transaction', "Application")
                                        ->where('appointment_status', "Pending")
                                        ->get();

                                    
        foreach($AllApplication as $chkApplication){
            if($chkApplication->applicant->last_name ==  $request->last_name &&
                $chkApplication->applicant->first_name ==  $request->first_name &&
                $chkApplication->applicant->middle_name ==  $request->middle_name &&
                $chkApplication->applicant->suffix_name ==  $request->suffix_name){
                
                Alert::html('Error', "This Applicant is already submit an application !", "error");

                return back()->withInput();        

            }
        }
        
        $barangay = Barangay::where('barangay_id', $request->barangay_id)->first();

        Appointment::create([
            'transaction' => 'Application',
            'appointment_date' => $request->appointment_date,
            'barangay_id' => $request->barangay_id
        ]);      
        
        $dateOfBirth = $request->birthday;
        $age = Carbon::parse($dateOfBirth)->age;

        NewApplicant::create([
            'appointment_id' => Appointment::all()->last()->appointment_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'suffix' => $request->suffix,
            'age' => $age,
            'birthday' => $request->birthday,
            'religion' => $request->religion,
            'ethnic_group' => $request->ethnic_group,
            'sex' => $request->sex,
            'civil_status' => $request->civil_status,
            'blood_type' => $request->blood_type,
            'disability_type' => $request->disability_type,
            'disability_cause' => $request->disability_cause,
            'disability_name' => $request->disability_name,
            'address' => $request->address,
            'barangay_id' => $barangay->barangay_id,
            'phone_number' => $request->phone_number,
            'telephone_number' => $request->telephone_number,
            'email' => $request->email,
            'educational_attainment' => $request->educational_attainment,
            'employment_status' => $request->employment_status,
            'employment_category' => $request->employment_category,
            'employment_type' => $request->employment_type,
            'occupation' => $request->occupation,
            'organization_affliated' => $request->organization_affliated,
            'organization_contact_person' => $request->organization_contact_person,
            'organization_office_address' => $request->organization_office_address,
            'organization_telephone_number' => $request->organization_telephone_number,
            'sss_number' => $request->sss_number,
            'gsis_number' => $request->gsis_number,
            'pagibig_number' => $request->pagibig_number,
            'philhealth_number' => $request->philhealth_number,
            'father_last_name' => $request->father_last_name,     
            'father_first_name' => $request->father_first_name,     
            'father_middle_name' => $request->father_middle_name,        
            'mother_last_name' => $request->mother_last_name,
            'mother_first_name' => $request->mother_first_name,
            'mother_middle_name' => $request->mother_middle_name,
            'guardian_last_name' =>  $request->guardian_last_name,
            'guardian_first_name' =>  $request->guardian_first_name,
            'guardian_middle_name' => $request->guardian_middle_name
        ]);

        $appointment = Appointment::all()->last();

        foreach (($request->med_cert) as $imagefile) {
            $image = new AppointmentDocs;
            $path = $imagefile->store('/', ['disk' =>   'images']);
            $image->img_name = $path;
            $image->docs_type = "Medical Certificate";
            $image->appointment = "Application";
            $image->appointment_id = $appointment->appointment_id;
            
            $image->save();
        }

        
        foreach (($request->voters) as $imagefile) {
            $image = new AppointmentDocs;
            $path = $imagefile->store('/', ['disk' =>   'images']);
            $image->img_name = $path;
            $image->docs_type = "Voters Confirmation";
            $image->appointment = "Application";
            $image->appointment_id = $appointment->appointment_id;
            
            $image->save();
        }

        if($request->authorization != null) {
            foreach (($request->authorization) as $imagefile) {
                $image = new AppointmentDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Authorization";
                $image->appointment = "Application";
                $image->appointment_id = $appointment->appointment_id;
                
                $image->save();
            }
        }      
                
        return redirect()->route('applicant.success', $appointment);

    } 

    public function applicationSuccess(Appointment $appointment){

        Alert::success('Success', 'Application Submit Success Please capture your reference number !');
        
        return view('landingpage/landingpages/appointments/application-success', compact('appointment'));

    }

    public function acceptApplicant($id){

        $appointment = Appointment::find($id);

        if($appointment->pictures->count() == 0){

            Alert::html('Error', 'Please upload a scanned copy of the applicant documents.', 'error');

            return back();

        }else{
            Pwd::create([
                'pwd_number' => null, 
                'last_name' => $appointment->applicant->last_name,
                'first_name' => $appointment->applicant->first_name,
                'middle_name' => $appointment->applicant->middle_name,
                'suffix' => $appointment->applicant->suffix,
                'age' => $appointment->applicant->age,
                'birthday' => $appointment->applicant->birthday,
                'religion' => $appointment->applicant->religion,
                'ethnic_group' => $appointment->applicant->ethnic_group,
                'sex' => $appointment->applicant->sex,
                'civil_status' => $appointment->applicant->civil_status,
                'blood_type' => $appointment->applicant->blood_type,
                'disability_type' => $appointment->applicant->disability_type,
                'disability_cause' => $appointment->applicant->disability_cause,
                'disability_name' => strtoupper($appointment->applicant->disability_name) ,
                'address' => $appointment->applicant->address,
                'barangay_id' => $appointment->barangay->barangay_id,
                'phone_number' => $appointment->applicant->phone_number,
                'telephone_number' => $appointment->applicant->telephone_number,
                'email' => $appointment->applicant->email,
                'educational_attainment' => $appointment->applicant->educational_attainment,
                'employment_status' => $appointment->applicant->employment_status,
                'employment_category' => $appointment->applicant->employment_category,
                'employment_type' => $appointment->applicant->employment_type,
                'occupation' => $appointment->applicant->occupation,
                'organization_affliated' => $appointment->applicant->organization_affliated,
                'organization_contact_person' => $appointment->applicant->organization_contact_person,
                'organization_office_address' => $appointment->applicant->organization_office_address,
                'organization_telephone_number' => $appointment->applicant->organization_telephone_number,
                'sss_number' => $appointment->applicant->sss_number,
                'gsis_number' => $appointment->applicant->gsis_number,
                'pagibig_number' => $appointment->applicant->pagibig_number,
                'philhealth_number' => $appointment->applicant->philhealth_number,
                'father_last_name' => $appointment->applicant->father_last_name,       
                'father_first_name' => $appointment->applicant->father_first_name,     
                'father_middle_name' => $appointment->applicant->father_middle_name,           
                'mother_last_name' => $appointment->applicant->mother_last_name,
                'mother_first_name' => $appointment->applicant->mother_first_name,
                'mother_middle_name' => $appointment->applicant->mother_middle_name,
                'guardian_last_name' => $appointment->applicant->guardian_last_name,
                'guardian_first_name' => $appointment->applicant->guardian_first_name,
                'guardian_middle_name' => $appointment->applicant->guardian_middle_name,
            ]);

            $pwd = Pwd::all()->last();

            $pwd->pwd_number = "04-000-".$pwd->pwd_id;

            $pwd->save();
    
            PwdStatus::create([
                'pwd_id' => $pwd->pwd_id,
                'id_expiration' => Carbon::now()->addYears(3)
            ]);

            foreach($appointment->pictures as $picture){
                PwdDocs::create([
                    'pwd_id' => $pwd->pwd_id,
                    'img_name' => $picture->img_name,
                    'docs_type' => $picture->docs_type,
                    'appointment' => $picture->appointment		
                ]);
            }

            AppointmentDocs::where('appointment_id', $appointment->appointment_id)->delete();

            Appointment::where('appointment_id', $appointment->appointment_id)
                        ->update(['appointment_status' => "Done"]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User accept scheduled applicant. Reference no. APL" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0"
            ]);            

            $transaction = '1';

            Session::put('hasID', null);

            Alert::success('Success', 'Transaction processed you may generate ID!');
            
            return redirect()->route('generate.id.page', [$pwd, $transaction] );
        }
    }

    public function renewalId(Request $request){

        $request->validate([
            'pwd_id' => 'required',
            'appointment_date' => 'required',
            'med_cert' => 'required|mimes:pdf',
            'authorization' => 'mimes:pdf'
        ]);

        if($this->checkAvaildate($request->appointment_date)){

            Alert::html('Error', 'Selected Date not availble', 'error');

            return back()->withInput();

        }

        $pwd = Pwd::where('pwd_number' , $request->pwd_id)->first();
        
        if($pwd->pwd_status->cancelled == 1){
            Alert::html('Error', "This PWD is already cancelled !", "error");
            return back()->withInput();
        }

        $allAppointment = Appointment::where('appointment_status', 'Pending')->get();

        foreach($allAppointment as $chkPWd){ 
            if($chkPWd->transaction == "Renewal ID"){
                if($chkPWd->renewal->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back()->withInput();
                }
            }elseif($chkPWd->transaction == "Lost ID"){
                if($chkPWd->lostId->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back();
                }
            }elseif($chkPWd->transaction == "Cancellation"){
                if($chkPWd->cancellation->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back()->withInput();
                }
            }
        }
                  
        if($pwd){
            $hasExistingRenewal = false;

            $ifExistingRenewal = Renewal::where('pwd_id', '=',  $pwd->pwd_id)->get()->last();
                
            if($ifExistingRenewal){

                $appointmentCheck =  DB::table('appointment')->where('appointment_id', $ifExistingRenewal->appointment_id)->get()->last();

                if($appointmentCheck->appointment_status === "Pending"){
                    $hasExistingRenewal == true;
                    return redirect()->back()->with('message',  'You are already submit an appointment for Renewal of ID.');
                }
            }
            if($hasExistingRenewal == false){
                if($pwd->pwd_status->id_expiration > date("Y-m-d")){
                    return redirect()->back()->with('message',  'Your ID is valid until ' . date('F j, Y', strtotime($pwd->pwd_status->id_expiration)). ' No need to renew');
                }
                else{
                    Appointment::create([
                        'transaction' => 'Renewal ID',
                        'appointment_date' => $request->appointment_date,
                        'barangay_id' => $pwd->barangay_id
                    ]);        
                    Renewal::create([
                        'appointment_id' => Appointment::all()->last()->appointment_id,
                        'pwd_id' => $pwd->pwd_id
                    ]);
                    
                    $appointment = Appointment::all()->last();

                    foreach (($request->med_cert) as $imagefile) {
                        $image = new AppointmentDocs;
                        $path = $imagefile->store('/', ['disk' =>   'images']);
                        $image->img_name = $path;
                        $image->docs_type = "Medical Certificate";
                        $image->appointment = "Renewal ID";
                        $image->appointment_id = $appointment->appointment_id;
                        
                        $image->save();
                    }
            
                    if($request->authorization != null) {
                        foreach (($request->authorization) as $imagefile) {
                            $image = new AppointmentDocs;
                            $path = $imagefile->store('/', ['disk' =>   'images']);
                            $image->img_name = $path;
                            $image->docs_type = "Authorization";
                            $image->appointment = "Renewal ID";
                            $image->appointment_id = $appointment->appointment_id;
                            
                            $image->save();
                        }
                    }
                                    
                    return redirect()->route('renewalID.success', $appointment);
                }      
            }
        }
        else{
            return redirect()->back()->withInput()->with('message', 'No Record Found ! Please Check your Details and try again.');
        }
    }

    public function renewalIdSuccess(Appointment $appointment){

        Alert::success('Success', 'Renewal ID Submit Success Please capture your reference number !');

        return view('landingpage/landingpages/appointments/renewal-success', compact('appointment'));

    }

    public function acceptRenewalId(Pwd $pwd, Appointment $appointment){

        if($appointment->pictures->count() == 0){

            Alert::html('Error', 'Please upload a scanned copy of the renewal documents.', 'error');

            return back();

        }else{

            PwdStatus::where('pwd_id', $pwd->pwd_id)
            ->update(['id_expiration' => Carbon::now()->addYears(3)]);

            PwdDocs::where('pwd_id', $pwd->pwd_id)
            ->where('appointment', 'Renewal ID')
            ->delete();

            foreach($appointment->pictures as $picture){
                PwdDocs::create([
                    'pwd_id' => $pwd->pwd_id,
                    'img_name' => $picture->img_name,
                    'docs_type' => $picture->docs_type,
                    'appointment' => $picture->appointment		
                ]);
            }

            $appointment->appointment_status = "Done";
            $appointment->save();

            $age = Carbon::parse($pwd->birthday)->age;
            $pwd->age = $age;
            $pwd->save();

            AppointmentDocs::where('appointment_id', $appointment->appointment_id)->delete();

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User processed an PWD ID renewal that had been scheduled. Reference no. RNW" .  date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0"
            ]);            

            $transaction = '2';

            Session::put('hasID', null);

            Alert::success('Success', 'Transaction processed you may generate ID!');
            
            return redirect()->route('generate.id.page', [$pwd, $transaction] );

        }
    }

    public function lostId(Request $request){

        $request->validate([
            'pwd_id' => 'required',
            'appointment_date' => 'required',
            'affidavit' => 'required|mimes:pdf',
            'authorization' => 'mimes:pdf'
        ]);
        
        if($this->checkAvaildate($request->appointment_date)){

            Alert::html('Error', 'Selected Date not availble', 'error');

            return back()->withInput();

        }

        $pwd = Pwd::where('pwd_number' , $request->pwd_id)->first();  
        
        if($pwd->pwd_status->cancelled == 1){
            Alert::html('Error', "This PWD is already cancelled !", "error");
            return back()->withInput();
        }
        
        $allAppointment = Appointment::where('appointment_status', 'Pending')->get();

        foreach($allAppointment as $chkPWd){ 
            if($chkPWd->transaction == "Renewal ID"){
                if($chkPWd->renewal->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back()->withInput();
                }
            }elseif($chkPWd->transaction == "Lost ID"){
                if($chkPWd->lostId->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back();
                }
            }elseif($chkPWd->transaction == "Cancellation"){
                if($chkPWd->cancellation->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back()->withInput();
                }
            }
        }

        if($pwd){

            if($pwd->pwd_status->id_expiration < date("Y-m-d")){
                return redirect()->back()->with('message',  'Your ID is expired proceed to renewal appointment !');
            }
            
            $hasExistingLostID = false;

            $ifExistingLostID  = LostId::where('pwd_id', '=',  $pwd->pwd_id)->get()->last();

            if($ifExistingLostID){

                $appointmentCheck =  DB::table('appointment')->where('appointment_id', $ifExistingLostID->appointment_id)->get()->last();

                if($appointmentCheck->appointment_status === "Pending"){

                    $hasExistingLostID == true;

                    return redirect()->back()->with('message',  'You are already submit an appointment for Lost of ID.');
                }
            }

            Appointment::create([
                    'transaction' => 'Lost ID',
                    'appointment_date' => $request->appointment_date,
                    'barangay_id' => $pwd->barangay_id
            ]);        

            LostId::create([
                'appointment_id' => Appointment::all()->last()->appointment_id,
                'pwd_id' => $pwd->pwd_id
            ]);
                        
            $appointment = Appointment::all()->last();

            foreach (($request->affidavit) as $imagefile) {
                $image = new AppointmentDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Affidavit Of Lost";
                $image->appointment = "Lost ID";
                $image->appointment_id = $appointment->appointment_id;
                
                $image->save();
            }
    
            if($request->authorization != null) {
                foreach (($request->authorization) as $imagefile) {
                    $image = new AppointmentDocs;
                    $path = $imagefile->store('/', ['disk' =>   'images']);
                    $image->img_name = $path;
                    $image->docs_type = "Authorization";
                    $image->appointment = "Lost ID";
                    $image->appointment_id = $appointment->appointment_id;
                    
                    $image->save();
                }
            }

            return redirect()->route('lostID.success', $appointment);
            
        }
        else{
            return redirect()->back()->withInput()->with('message', 'No Record Found ! Please Check your Details and try again.');
        }
        
    }
    
    public function lostIdSuccess(Appointment $appointment){

        Alert::success('Success', 'Lost ID Submit Success, Please capture your reference number !');

        return view('landingpage/landingpages/appointments/lost-id-success', compact('appointment'));

    }

    public function acceptLostId($id, Appointment $appointment){

        $checkAppointmentDocs = AppointmentDocs::where('appointment_id', $appointment->appointment_id)
                                                ->where('docs_type', 'Affidavit Of Lost')
                                                ->get();

        if($checkAppointmentDocs->count() == 0){

            Alert::html('Error', 'Please upload a scanned copy of the lost ID documents.', 'error');

            return back();
        }
        else{

            $pwd = Pwd::find($id);

            PwdStatus::where('pwd_id', $id)
            ->update(['id_expiration' => Carbon::now()->addYears(3)]);

            foreach($appointment->pictures as $picture){
                PwdDocs::create([
                    'pwd_id' => $pwd->pwd_id,
                    'img_name' => $picture->img_name,
                    'docs_type' => $picture->docs_type,
                    'appointment' => $picture->appointment		
                ]);
            }

            $appointment->appointment_status = "Done";
            $appointment->save();
                        
            $age = Carbon::parse($pwd->birthday)->age;

            $pwd->age = $age;
            $pwd->save();
            AppointmentDocs::where('appointment_id', $appointment->appointment_id)->delete();

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User processed an Lost of PWD ID that had been scheduled. Reference no. LST" . date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0"
            ]);    

            $transaction = '3';

            Session::put('hasID', null);

            Alert::success('Success', 'Transaction processed you may generate ID!');
            
            return redirect()->route('generate.id.page', [$pwd, $transaction] );
        }
    }

    public function cancellation(Request $request){

        $request->validate([
            'pwd_id' => 'required',
            'appointment_date' => 'required',
            // 'Id' => 'required|mimes:pdf',
            'authorization' => 'mimes:pdf'
            
        ]);
        
        if($this->checkAvaildate($request->appointment_date)){

            Alert::html('Error', 'Selected Date not availble', 'error');

            return back()->withInput();

        }

        $pwd = Pwd::where('pwd_number' , $request->pwd_id)->first();
        
        if($pwd->pwd_status->cancelled == 1){
            Alert::html('Error', "This PWD is already cancelled !", "error");
            return back()->withInput();
        }

        $allAppointment = Appointment::where('appointment_status', 'Pending')->get();

        foreach($allAppointment as $chkPWd){ 
            if($chkPWd->transaction == "Renewal ID"){
                if($chkPWd->renewal->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back()->withInput();
                }
            }elseif($chkPWd->transaction == "Lost ID"){
                if($chkPWd->lostId->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back();
                }
            }elseif($chkPWd->transaction == "Cancellation"){
                if($chkPWd->cancellation->pwd_id == $pwd->pwd_id){
                    Alert::html('Error', "This PWD has an pending appointment !", "error");
                    return back()->withInput();
                }
            }
        }

        if($pwd){
            $hasExistingLostID = false;

            $ifExistingLostID  = Cancellation::where('pwd_id', '=',  $pwd->pwd_id)->get()->last();

            if($ifExistingLostID){

                 $appointmentCheck =  DB::table('appointment')->where('appointment_id', $ifExistingLostID->appointment_id)->get()->last();

                if($appointmentCheck->appointment_status === "Pending"){

                    $hasExistingLostID == true;

                    return redirect()->back()->with('message',  'You are already submit an appointment for Cancellation.');
                }
            }

            Appointment::create([
                'transaction' => 'Cancellation',
                'appointment_date' => $request->appointment_date,
                'barangay_id' => $pwd->barangay_id
            ]);        

            Cancellation::create([
                'appointment_id' => Appointment::all()->last()->appointment_id,
                'pwd_id' => $pwd->pwd_id
            ]);
                        
            $appointment = Appointment::all()->last();

                foreach (($request->Id) as $imagefile) {
                    $image = new AppointmentDocs;
                    $path = $imagefile->store('/', ['disk' =>   'images']);
                    $image->img_name = $path;
                    $image->docs_type = "PWD ID";
                    $image->appointment = "Cancellation";
                    $image->appointment_id = $appointment->appointment_id;
                    
                    $image->save();
                }
        
                if($request->authorization != null) {
                    foreach (($request->authorization) as $imagefile) {
                        $image = new AppointmentDocs;
                        $path = $imagefile->store('/', ['disk' =>   'images']);
                        $image->img_name = $path;
                        $image->docs_type = "Authorization";
                        $image->appointment = "Cancellation";
                        $image->appointment_id = $appointment->appointment_id;
                        
                        $image->save();
                    }
                }

                return redirect()->route('cancellation.success', $appointment);
          
        }
        else{
            return redirect()->back()->withInput()->with('message', 'No Record Found ! Please Check your Details and try again.');
        }
        
    }

    public function cancellationSuccess(Appointment $appointment){

        Alert::success('Success', 'Request for Cancellation Submit Success, Please capture your reference number !');

        return view('landingpage/landingpages/appointments/cancellation-success', compact('appointment'));

    }

    public function acceptCancellation(Pwd $pwd, Appointment $appointment){

        if($appointment->pictures->count() == 0){

            Alert::html('Error', 'Please upload a scanned copy of the cancellation documents.', 'error');

            return back();

        }else{

            PwdStatus::where('pwd_id', $pwd->pwd_id)
            ->update(['cancelled' => 1]);

            foreach($appointment->pictures as $picture){
                PwdDocs::create([
                    'pwd_id' => $pwd->pwd_id,
                    'img_name' => $picture->img_name,
                    'docs_type' => $picture->docs_type,
                    'appointment' => $picture->appointment		
                ]);
            }

            $appointment->appointment_status = "Done";
            $appointment->save();

            $age = Carbon::parse($pwd->birthday)->age;
            $pwd->age = $age;
            $pwd->save();

            AppointmentDocs::where('appointment_id', $appointment->appointment_id)->delete();

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User processed an PWD cancellation that had been scheduled. Reference no. RCL" . date('j', strtotime($appointment->appointment_date ))."-0". $appointment->barangay_id."-".$appointment->appointment_id."0"
            ]);            

            Alert::success('Success', 'Transaction processed you may generate Cancelation Letter !');
            
            return redirect()->route('generate.cancelled.letter', $pwd );

        }
    }
    
    public function generateCancelledLetter(Pwd $pwd){

        $idFile = Signatory::where('signatory_type', 'Cancellation')->first()->img_file;
        
        $cancellationSignatory = "signatory/".$idFile;

        Alert::success('Success', 'Transaction processed you may Cancellation Letter');

        return view('userpages/staff-pages/generate-cancellation-page', compact('pwd', 'cancellationSignatory'));

    }

}


