<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Pwd;
use App\Models\Barangay;
use App\Models\Account;
use App\Models\Renewal;
use App\Models\PwdStatus;
use App\Models\Appointment;
use App\Models\ActivityLog;
use App\Models\PwdDocs;
use App\Models\WalkIn;
use App\Models\LostId;
use App\Models\Signatory;
use PDF;
use Session;
use Dompdf\Dompdf;
use Carbon\Carbon;
use File;
use Storage;
use Illuminate\Validation\Rule;
use Validator;

class PwdManagementController extends Controller
{
    public function autoCompleteDisease(Request $request){
        if ($request->ajax()) {
            $data = Pwd::where('disability_name','LIKE', $request->disability_name.'%')->get();
            $result = $data->first();
            $output = '';
            if ($result) {
                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
                    $output .= '<li class="list-group-item items">'.$result->disability_name.'</li>';
                $output .= '</ul>';
            }else {
                $output .= '<li class="list-group-item">'.'No Data Found'.'</li>';
            }
            return $output;
        }
        return view('autosearch');  

    }

    public function signatoryPage(){

        $activePage = "ID";

        $id = Signatory::where('signatory_type', 'ID')->first();
        $cancel = Signatory::where('signatory_type', 'Cancellation')->first();

        return view('userpages/signatory-management/signatory', compact('activePage', 'id', 'cancel'));

    }

    public function uploadIDSignature(Request $request){

        $request->validate([
            'signature' => 'required|mimes:png',
        ]);

        if($file = $request->hasFile('signature')) {
             
            $file = $request->file('signature') ;
            $fileName = $request->file('signature')->getClientOriginalName() ;
            $destinationPath = public_path('/signatory');
            $file->move($destinationPath,$fileName);

            $activePage = "ID";

            $oldSignatory = Signatory::where('signatory_type', 'ID')->first();
    
            if ($oldSignatory != null && file_exists(public_path('/signatory/'.$oldSignatory->img_file))){
                $filedeleted = unlink(public_path('/signatory/'.$oldSignatory->img_file));
                $oldSignatory->delete();
             }

             Signatory::create([
                 'img_id' => 1,
                'img_file' => $fileName,
                'signatory_type' => 'ID'
             ]); 

             $id = Signatory::where('signatory_type', 'ID')->first();
             $cancel = Signatory::where('signatory_type', 'Cancellation')->first();

             return view('userpages/signatory-management/signatory', compact('activePage', 'id', 'cancel'));
            }
    }

    public function uploadCancelationSignature(Request $request){

        $request->validate([
            'signature' => 'required|mimes:png',
        ]);

        if($file = $request->hasFile('signature')) {
             
            $file = $request->file('signature') ;
            $fileName = $request->file('signature')->getClientOriginalName() ;
            $destinationPath = public_path('/signatory');
            $file->move($destinationPath,$fileName);

            $activePage = "Cancelation";

            $oldSignatory = Signatory::where('signatory_type', 'Cancellation')->first();
    
            if ($oldSignatory != null && file_exists(public_path('/signatory/'.$oldSignatory->img_file))){
                $filedeleted = unlink(public_path('/signatory/'.$oldSignatory->img_file));
                $oldSignatory->delete();
             }

             Signatory::create([
                'img_file' => $fileName,
                'signatory_type' => 'Cancellation'
             ]); 

             $id = Signatory::where('signatory_type', 'ID')->first();
             $cancel = Signatory::where('signatory_type', 'Cancellation')->first();

             return view('userpages/signatory-management/signatory', compact('activePage', 'id', 'cancel'));
        }
    }

    public function uploadIDPicture(Request $request){

        $request->validate([
            'picture' => 'required|image',
        ]);

        if($file = $request->hasFile('picture')) {
             
            $file = $request->file('picture') ;
            $fileName = (string)Signatory::all()->last()->img_id. $request->file('picture')->getClientOriginalName();

            $destinationPath = public_path('/signatory');
            $file->move($destinationPath,$fileName);

            $oldID = Signatory::where('signatory_type', 'IDPIC')->first();
            
            if ($oldID != null && file_exists(public_path('/signatory/'.$oldID->img_file))){
                $filedeleted = unlink(public_path('/signatory/'.$oldID->img_file));
                $oldID->delete();
             }

             Signatory::create([
                'img_file' => $fileName,
                'signatory_type' => 'IDPIC'
             ]); 

            Session::put('hasID', 1);

            Alert::success('Success', 'Picture Upload Successfully !');

            $pwd = Pwd::where('pwd_id', $request->pwd)->first();
            $transaction = $request->transaction;

            $idFile = Signatory::where('signatory_type', 'ID')->first()->img_file;
            $idDetails = "signatory/".$idFile;

            $idDetailsPic = Signatory::where('signatory_type', 'IDPIC')->first()->img_file;
            $IDPic = "signatory/".$idDetailsPic;

            return view('userpages/staff-pages/generate-id-page', compact('pwd', 'transaction', 'idDetails', 'IDPic'));
        }
    }

    public function pwdManagementPage(){
        $barangays = Barangay::all();

        if(Auth::user()->user_role ==  2|| Auth::user()->user_role == 0 ){
            $pwd = Pwd::all();
            return view('userpages/pwdmanagement/pwd-management', compact('pwd', 'barangays'));
        }
        else{    
            $pwd = Pwd::where('barangay_id', Auth::user()->barangay->barangay_id)->get();

            $active = 0;
            $inactive = 0;
            $cancelled = 0;

            foreach($pwd as $selectedPwd){
                if($selectedPwd->pwd_status->id_expiration > date("Y-m-d") && $selectedPwd->pwd_status->cancelled == 0)
                    $active++;
                elseif($selectedPwd->pwd_status->id_expiration < date("Y-m-d") && $selectedPwd->pwd_status->cancelled == 0)
                    $inactive++;
                else
                    $cancelled++;

            }

            return view('userpages/pwdmanagement/pwd-management', compact('pwd', 'barangays', 'active', 'inactive', 'cancelled'));
        }   
    }

    public function viewPwd($id){

        $pwd = Pwd::where('pwd_id', '=', $id)->first(); 

        return view('userpages/pwdmanagement/view-pwd', compact('pwd'));

    }

    public function updatePwd($id){
        
        $pwd = Pwd::where('pwd_id', '=', $id)->first();

        return view('userpages/pwdmanagement/update-pwd', compact('pwd'));
    }

    public function updateSave(Request $request, Pwd $pwd){

        if(Pwd::where('email', $request->email)->first() && $pwd->email != $request->email )
        {
            Alert::html('Error', 'Email Address are Registered already', 'error');
            return back()->withInput();
        }

        $pwd->update($request->all());

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User edit " . $pwd->first_name ." " . $pwd->last_name. " information."
        ]);  

        Alert::success('Success', 'PWD Update Details Success');

        return redirect()->route('view.pwd' ,$pwd->pwd_id)->with('message', 'Updated Sucessfully');

    }

    public function walkInPwd(Request $request){

        $validator = Validator::make($request->all(), [
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
            'email' => 'required|email|max:255|unique:pwd'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                    ->withInput();
        }

        $barangay = Barangay::where('barangay_id', $request->barangay_id)->first();
        $dateOfBirth = $request->birthday;
        $age = Carbon::parse($dateOfBirth)->age;

        Pwd::create([
                'pwd_number' => null, 
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
                'disability_name' => strtoupper($request->disability_name),
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
                'organization_contact_person' =>$request->organization_contact_person,
                'organization_office_address' => $request->organization_office_address,
                'organization_telephone_number' => $request->organization_telephone_number,
                'sss_number' => $request->sss_number,
                'gsis_number' => $request->gsis_number,
                'pagibig_number' => $request->pagibig_number,
                'philhealth_number' =>$request->philhealth_number,
                'father_last_name' => $request->father_last_name,     
                'father_first_name' => $request->father_first_name,     
                'father_middle_name' => $request->father_middle_name,        
                'mother_last_name' => $request->mother_last_name,
                'mother_first_name' => $request->mother_first_name,
                'mother_middle_name' => $request->mother_middle_name,
                'guardian_last_name' =>  $request->guardian_last_name,
                'guardian_first_name' => $request->guardian_first_name,
                'guardian_middle_name' => $request->guardian_middle_name
        ]);
    
        $pwd = Pwd::all()->last();

        $pwd->pwd_number = "04-000-".$pwd->pwd_id;
        $pwd->save();

        PwdStatus::create([
            'pwd_id' =>  $pwd->pwd_id,
            'id_expiration' => Carbon::now()->addYears(3)
        ]);

        WalkIn::create([
            'pwd_id' =>  $pwd->pwd_id,
            'barangay_id' => $pwd->barangay->barangay_id,
            'transaction' => 'Application'
        ]);
            
        foreach (($request->med_cert) as $imagefile) {
            $image = new PwdDocs;
            $path = $imagefile->store('/', ['disk' =>   'images']);
            $image->img_name = $path;
            $image->docs_type = "Medical Certificate";
            $image->appointment = "Application";
            $image->pwd_id = $pwd->pwd_id;
                
            $image->save();
        }
            
        foreach(($request->voters) as $imagefile) {
            $image = new PwdDocs;
            $path = $imagefile->store('/', ['disk' =>   'images']);
            $image->img_name = $path;
            $image->docs_type = "Voters Confirmation";
            $image->appointment = "Application";
            $image->pwd_id = $pwd->pwd_id;
                
            $image->save();
        }
    
        if($request->authorization != null) {
            foreach (($request->authorization) as $imagefile) {
                $image = new PwdDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Authorization";
                $image->appointment = "Application";
                $image->pwd_id = $pwd->pwd_id;
                    
                $image->save();
            }
        }      

         ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User proccessed walk-in application transaction. PWD ID " .  $pwd->pwd_number
        ]); 

        $transaction = '1';

        Session::put('hasID', null);

        Alert::success('Success', 'Transaction processed you may generate ID!');

        return redirect()->route('generate.id.page', [$pwd, $transaction] );

    }

    public function applicantGenerateID(Pwd $pwd){

        Alert::success('Success', 'PWD Added Successfully you may generate ID!');

        return view('userpages/staff-pages/walkin-transaction/walk-in-applicant-validate', compact('pwd'));

    }

    public function walkInRenewal(Pwd $pwd){

        $hasAppointmentRenewal = Appointment::where("transaction", 'Renewal ID')->get();
        $hasAppointmentLostId = Appointment::where('transaction', 'Lost ID')->get();
        $hasCancellation = Appointment::where('transaction', 'Cancellation')->get();

        $hasAppointment = false;

        foreach ($hasAppointmentRenewal as $appointment) {
            if($appointment->renewal->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        foreach ($hasAppointmentLostId as $appointment) {
            if($appointment->lostId->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        foreach ($hasCancellation as $appointment) {
            if($appointment->cancellation->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        if($pwd->pwd_status->id_expiration > date("Y-m-d")){
            Alert::html('Error', 'The ID of this PWD is not expired no need to renew it !', 'error');
            return back();
        }
        if($hasAppointment == true){
            Alert::html('Error', 'This PWD set an appointment !', 'error');
            return back();
        }


        return view('userpages/staff-pages/walkin-transaction/walk-in-renewal', compact('pwd'));

    }

    public function acceptRenewal(Request $request, Pwd $pwd){

        if($request->med_cert == null){
            Alert::html('Error', 'Please upload a scanned copy of the Renewal ID documents.', 'error');
            return back();
        }
        else{

            foreach (($request->med_cert) as $imagefile) {
                $image = new PwdDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Medical Certificate";
                $image->appointment = "Renewal ID";
                $image->pwd_id = $pwd->pwd_id;
                    
                $image->save();
            }

            if($request->authorization != null) {
                foreach (($request->authorization) as $imagefile) {
                    $image = new PwdDocs;
                    $path = $imagefile->store('/', ['disk' =>   'images']);
                    $image->img_name = $path;
                    $image->docs_type = "Authorization";
                    $image->appointment = "Renewal ID";
                    $image->pwd_id = $pwd->pwd_id;
                        
                    $image->save();
                }
            } 
                
            PwdStatus::where('pwd_id', $pwd->pwd_id)
            ->update(['id_expiration' => Carbon::now()->addYears(3)]);

            WalkIn::create([
                'pwd_id' =>  $pwd->pwd_id,
                'barangay_id' =>$pwd->barangay->barangay_id,
                'transaction' => 'Renewal ID'
            ]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User proccessed walk-in renewal ID transaction. PWD ID " .  $pwd->pwd_number
            ]); 

            $transaction = '2';

            Session::put('hasID', null);

            Alert::success('Success', 'Transaction processed you may generate ID!');

            return redirect()->route('generate.id.page', [$pwd, $transaction] );
        }

    }

    public function walkInLostID(Pwd $pwd){

        $hasAppointmentRenewal = Appointment::where("transaction", 'Renewal ID')->get();
        $hasAppointmentLostId = Appointment::where('transaction', 'Lost ID')->get();
        $hasCancellation = Appointment::where('transaction', 'Cancellation')->get();
        
        $hasAppointment = false;

        foreach ($hasAppointmentRenewal as $appointment) {
            if($appointment->renewal->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        foreach ($hasAppointmentLostId as $appointment) {
            if($appointment->lostId->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        foreach ($hasCancellation as $appointment) {
            if($appointment->cancellation->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        if($pwd->pwd_status->id_expiration < date("Y-m-d")){
            Alert::html('Error', 'The ID of this PWD is expired proceed to renewal transaction !', 'error');
            return back();
        }

        if($hasAppointment == true){
            Alert::html('Error', 'This PWD set an appointment !', 'error');
            return back();
        }

        return view('userpages/staff-pages/walkin-transaction/walk-in-lostid', compact('pwd'));
    }

    public function acceptLostID(Request $request, Pwd $pwd){

        if($request->affidavit == null){
            Alert::html('Error', 'Please upload a scanned copy of the Lost ID documents.', 'error');
            return back();
        }
        else{

            $hasLostIDDocs = PwdDocs::where('pwd_id', $pwd->pwd_id)
                            ->where('appointment', 'Lost ID')
                            ->delete();

            foreach (($request->affidavit) as $imagefile) {
                $image = new PwdDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "Affidavit Of Lost";
                $image->appointment = "Lost ID";
                $image->pwd_id = $pwd->pwd_id;
                
                $image->save();
            }
    
            if($request->authorization != null) {
                foreach (($request->authorization) as $imagefile) {
                    $image = new PwdDocs;
                    $path = $imagefile->store('/', ['disk' =>   'images']);
                    $image->img_name = $path;
                    $image->docs_type = "Authorization";
                    $image->appointment = "Lost ID";
                    $image->pwd_id = $pwd->pwd_id;
                        
                    $image->save();
                }
            }
                
            PwdStatus::where('pwd_id', $pwd->pwd_id) 
            ->update(['id_expiration' => Carbon::now()->addYears(3)]);

            WalkIn::create([
                'pwd_id' =>  $pwd->pwd_id,
                'barangay_id' =>$pwd->barangay->barangay_id,
                'transaction' => 'Lost ID'
            ]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User proccessed walk-in Lost ID transaction. PWD ID " .  $pwd->pwd_number
            ]); 
    
            $transaction = '3';
            
            Session::put('hasID', null);

            Alert::success('Success', 'Transaction processed you may generate ID!');

            return redirect()->route('generate.id.page', [$pwd, $transaction] );

        }
    }

    public function walkInCancellation(Pwd $pwd){

        $hasAppointmentRenewal = Appointment::where("transaction", 'Renewal ID')->get();
        $hasAppointmentLostId = Appointment::where('transaction', 'Lost ID')->get();
        $hasCancellation = Appointment::where('transaction', 'Cancellation')->get();
        
        $hasAppointment = false;

        foreach ($hasAppointmentRenewal as $appointment) {
            if($appointment->renewal->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        foreach ($hasAppointmentLostId as $appointment) {
            if($appointment->lostId->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        foreach ($hasCancellation as $appointment) {
            if($appointment->cancellation->pwd_id == $pwd->pwd_id){
                if($appointment->appointment_status == "Pending")   
                    $hasAppointment = true;
            }
        }

        if($hasAppointment == true){
            Alert::html('Error', 'This PWD set an appointment !', 'error');
            return back();
        }

        return view('userpages/staff-pages/walkin-transaction/walk-in-cancellation', compact('pwd'));

    }
    
    public function acceptCancellation(Request $request, Pwd $pwd){

        if($request->Id == null){
            Alert::html('Error', 'Please upload a scanned copy of the PWD Cancellation documents.', 'error');
            return back();
        }
        else{

            $hasLostIDDocs = PwdDocs::where('pwd_id', $pwd->pwd_id)
            ->where('appointment', 'Cancellation')
            ->delete();

            foreach (($request->Id) as $imagefile) {
                $image = new PwdDocs;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->docs_type = "PWD ID";
                $image->appointment = "Cancellation";
                $image->pwd_id = $pwd->pwd_id;
                
                $image->save();
            }
    
            if($request->authorization != null) {
                foreach (($request->authorization) as $imagefile) {
                    $image = new PwdDocs;
                    $path = $imagefile->store('/', ['disk' =>   'images']);
                    $image->img_name = $path;
                    $image->docs_type = "Authorization";
                    $image->appointment = "Cancellation";
                    $image->pwd_id = $pwd->pwd_id;
                        
                    $image->save();
                }
            }
                
            PwdStatus::where('pwd_id', $pwd->pwd_id) 
                        ->update(['cancelled' => 1, 'cancelled_date' => Carbon::now()]);
                      

            WalkIn::create([
                'pwd_id' =>  $pwd->pwd_id,
                'barangay_id' =>$pwd->barangay->barangay_id,
                'transaction' => 'Cancellation'
            ]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User proccessed walk-in PWD Cancellation. PWD Number " .  $pwd->pwd_number
            ]); 

            return redirect()->route('generate.cancelled.letter', $pwd );
        }
    }

    public function walkInHistory(){

        $walkIn = WalkIn::all();

        return view('userpages/staff-pages/walkin-transaction/walk-in-history', compact('walkIn'));
    }

    public function viewPwdDocs (Pwd $pwd){

       $applicationDocs = $pwd->pictures->where('appointment', "Application");
       $renewalDocs = $pwd->pictures->where('appointment', "Renewal ID");
       $lostIdDocs = $pwd->pictures->where('appointment', "Lost ID");
       $cancellationDocs = $pwd->pictures->where('appointment', "Cancellation");

        return view('userpages/pwdmanagement/pwd-docs', compact('pwd','applicationDocs','renewalDocs','lostIdDocs','cancellationDocs'));

    }

    public function printPWD(Pwd $pwd){

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/report-template/pwd', compact('pwd'));
    
        $pdf->render();

        return $pdf->download('PWD Details ' . $pwd->last_name .'.pdf');
    
}

}
