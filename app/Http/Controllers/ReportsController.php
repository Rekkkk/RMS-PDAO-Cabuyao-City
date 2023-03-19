<?php

namespace App\Http\Controllers;

use PDF;
use Dompdf\Dompdf;
use App\Models\Pwd;
use App\Models\PwdStatus;
use App\Models\Barangay;
use App\Models\Program;
use App\Models\ProgramClaimant;
use App\Models\Appointment;
use App\Models\WalkIn;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Collection;
use Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;


class ReportsController extends Controller
{

    public function pwdStatusGraph(Request $request, Barangay $barangay){
        
        if($file = $request->hasFile('file')) {
             
            $file = $request->file('file') ;
            $fileName = 'graph.png' ;
            $destinationPath = public_path();
            $file->move($destinationPath,$fileName);
        }

        if($file = $request->hasFile('yealyChart')) {
             
            $file = $request->file('yealyChart') ;
            $fileName = 'yealyChart.png' ;
            $destinationPath = public_path();
            $file->move($destinationPath,$fileName);
        }

        $category = $request->category;
        $added_sex = $request->added_sex;
        $deducted_sex = $request->deducted_sex;
        $added_age = $request->added_age;
        $deducted_age = $request->deducted_age;
        $newPwdPercent = $request->newPwd;
        $deductedPwdPercent = $request->deductedPwd;
        $newPwdsJson = $request->added_pwds;
        $deductedPwdsJson = $request->deducted_pwds;
        
        $addedPwd = new Collection;
        $deductedPwd = new Collection;

        $addedPwdJson = json_decode($newPwdsJson);
        $deductedPwdJson = json_decode($deductedPwdsJson);

        foreach( $addedPwdJson as $addedPwds => $pwd) {
            $addedPwd->push($pwd);
        }

        foreach( $deductedPwdJson as $deductedPwds => $pwd) {
            $deductedPwd->push($pwd);
        }

        $disabilitTypes = ["Deaf/Hard of hearing",
                            "Intellectual Disability", 
                            "Learning Disability imparement", 
                            "Mental Disability", 
                            "Physical Disability", 
                            "Psychosocial Disability", 
                            "Speech and Language", 
                            "Visual Disablity"
                        ];  

        $disabiltyCount = [0, 0, 0, 0, 0, 0, 0, 0];
        
        foreach($addedPwd as $newPWD){
            if($newPWD->disability_type == $disabilitTypes[0]){
                $disabiltyCount[0]++;
            }elseif($newPWD->disability_type == $disabilitTypes[1]){
                $disabiltyCount[1]++;
            }elseif($newPWD->disability_type == $disabilitTypes[2]){
                $disabiltyCount[2]++;
            }elseif($newPWD->disability_type == $disabilitTypes[3]){
                $disabiltyCount[3]++;
            }elseif($newPWD->disability_type == $disabilitTypes[4]){
                $disabiltyCount[4]++;
            }elseif($newPWD->disability_type == $disabilitTypes[5]){
                $disabiltyCount[5]++;
            }elseif($newPWD->disability_type == $disabilitTypes[6]){
                $disabiltyCount[6]++;
            }elseif($newPWD->disability_type == $disabilitTypes[7]){
                $disabiltyCount[7]++;
            }
        }

        $disabilitType = [round((max($disabiltyCount) / $addedPwd->count()) * 100) ,$disabilitTypes[array_search(max($disabiltyCount), $disabiltyCount)]];

        $pdf = app('dompdf.wrapper');

        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/report-template/pwd-report-template', 
                        compact('added_sex', 'added_age', 'deducted_sex', 'addedPwd', 'deductedPwd', 
                        'deducted_age', 'disabilitType', 'barangay', 'category', 'newPwdPercent' ,'deductedPwdPercent'));
    
        $pdf->render();

        return $pdf->download('PWD Report.pdf');
    }

    public function transactionGraph(Request $request){

        if($file = $request->hasFile('graph1')) {
             
            $file = $request->file('graph1') ;
            $fileName = 'transaction-graph1.png' ;
            $destinationPath = public_path();
            $file->move($destinationPath,$fileName);
        }

        if($file = $request->hasFile('graph2')) {
             
            $file = $request->file('graph2') ;
            $fileName = 'transaction-graph2.png' ;
            $destinationPath = public_path();
            $file->move($destinationPath,$fileName);
        }

        $schedules = new Collection;
        $walkIns = new Collection;

        $scheduledJson = json_decode($request->scheduled);
        $walkInJson = json_decode($request->walk_in);

        foreach( $scheduledJson as $schedule => $pwd) {
            $schedules->push($pwd);
        }

        foreach( $walkInJson as $walkIn => $pwd) {
            $walkIns->push($pwd);
        }
        
        $pdf = app('dompdf.wrapper');

        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/report-template/appointment-report-template', compact('schedules', 'walkIns'));
    
        $pdf->render();

        return $pdf->download('Office Transaction Report.pdf');
    }

    public function generatePWDStatusReports(Request $request){ 

        $pwds;
        $barangay;

        if(Auth::user()->user_role == 2){
            
            if($request->barangay_id == null){
                Alert::html('Error', 'Please select barangay ', 'error');
                return back();
            }
            if($request->barangay_id == 20){

                $pwds = Pwd::all();
                $barangay = Barangay::findOrFail(1); 

            }
            else{
                $pwds = Pwd::where('barangay_id', $request->barangay_id)->get();
                $barangay = Barangay::where('barangay_id', $request->barangay_id)->first();
            }  
        }
        else{
            $pwds = Pwd::where('barangay_id', Auth::user()->barangay_id)->get();
            $barangay = Barangay::where('barangay_id', Auth::user()->barangay_id)->first();
        }

        $category = $request->status_category;
        $valueNewPwd = [];
        $valueDeductedPwd = [];
        $totalPopulation = [];

        // $dateStart = null;
        // $dateEnd =  null;
        $addedMale = 0;
        $deductedMale = 0;
        $addedMinors = 0;
        $deductedMinors = 0;
        $info = [];
        $addedPwd = new Collection;
        $deductedPwd = new Collection;

        
        if($category == "status_category1"){
            $year = [] ;
            $currentYear = (int)$request->status_start_year;    
            $totalDeductedPwdCount = 0;
            for($i = (int)$request->status_start_year; $i <= (int)$request->status_end_year; $i++){
                $totalNewPwd = 0;
                $totalPopulationCount = 0 ;
                $totalDeductedPwd = 0;
                foreach($pwds as $pwd){
                    if(date('Y', strtotime($pwd->created_at)) == $currentYear)  {
                        if($pwd->sex == "Male"){
                            $addedMale++;
                        }
                        if($pwd->age < 19){
                            $addedMinors++;
                        }
                        $addedPwd->push($pwd);
                        $totalNewPwd++;  
                    }  

                    if(date('Y', strtotime($pwd->pwd_status->cancelled_date)) == $currentYear){
                        if($pwd->sex == "Male"){
                            $deductedMale++;
                        }
                        if($pwd->age < 19){
                            $deductedMinors++;
                        }
                        $deductedPwd->push($pwd);
                        $totalDeductedPwd++;
                    } 

                    if(date('Y', strtotime($pwd->created_at)) < $currentYear) {
                        if($pwd->pwd_status->cancelled_date == null){
                            $totalPopulationCount ++;
                        }elseif(date('Y', strtotime($pwd->pwd_status->cancelled_date)) >= $currentYear){
                            $totalPopulationCount ++;
                        }
                    } 
                }     
                
                array_push($totalPopulation, $totalPopulationCount);
                array_push($valueNewPwd, $totalNewPwd); 
                array_push($valueDeductedPwd, $totalDeductedPwd);
                array_push($year, $currentYear);
                $currentYear++;
            }

            array_push($info, $addedMale);
            array_push($info, $deductedMale);
            array_push($info, $addedMinors);
            array_push($info, $deductedMinors);

            // return view('userpages/report-template/all-barangay-status', 
            //             compact('info', 'category','statusDatas', 'pwds', 
            //             'barangay', 'year', 'totalPopulation' ,'valueNewPwd', 'valueDeductedPwd', 
            //             'dateStart' ,'dateEnd', 'addedPwd' ,'deductedPwd'));

            // dd(start($year));

            return view('userpages/report-template/all-barangay-status', 
                        compact('info', 'category', 'pwds', 
                        'barangay', 'year', 'totalPopulation' ,'valueNewPwd', 'valueDeductedPwd', 
                        'addedPwd' ,'deductedPwd'));

        }
        elseif($category == "status_category2"){

            $year =  (int)$request->monthly_year;
            $monthValue = [];
            for($i = 0; $i < 12; $i++){
                $totalNewPwd = 0;
                $totalDeductedPwd = 0;                    
                $totalPopulationCount = 0;
                foreach($pwds as $pwd){
                    if($i < 9){
                        if(date('Y-m', strtotime($pwd->created_at)) < $year.'-0'.$i+1){
                            if($pwd->pwd_status->cancelled_date == null){
                                $totalPopulationCount++;
                            }elseif(date('Y-m', strtotime($pwd->pwd_status->cancelled_date)) >= $year.'-0'.$i+1){
                                $totalPopulationCount++;
                            }   
                        }
                    }else{
                        if(date('Y-m', strtotime($pwd->created_at)) < $year.'-'.$i+1){
                            if($pwd->pwd_status->cancelled_date == null){
                                $totalPopulationCount++;
                            }elseif(date('Y-m', strtotime($pwd->pwd_status->cancelled_date)) >= $year.'-'.$i+1){
                                $totalPopulationCount++;
                            }   
                        }
                    }
                    if(date('Y', strtotime($pwd->created_at)) == $year)  {
                        if(date('m', strtotime($pwd->created_at)) == $i+1){
                            if($pwd->sex == "Male"){
                                $addedMale++;
                            }
                            if($pwd->age < 19){
                                $addedMinors++;
                            }
                            $addedPwd->push($pwd);
                            $totalNewPwd++;
                        }
                    }  
                    if(date('Y', strtotime($pwd->pwd_status->cancelled_date)) == $year){
                        if(date('m', strtotime($pwd->pwd_status->cancelled_date)) == $i+1){
                            if($pwd->sex == "Male"){
                                $deductedMale++;
                            }
                            if($pwd->age < 19){
                                $deductedMinors++;
                            }
                            $deductedPwd->push($pwd);
                            $totalDeductedPwd++;
                        }
                    }
                    
                }
                array_push($totalPopulation, $totalPopulationCount);
                array_push($valueNewPwd, $totalNewPwd);
                array_push($valueDeductedPwd, $totalDeductedPwd);
            }       

            array_push($info, $addedMale);
            array_push($info, $deductedMale);
            array_push($info, $addedMinors);
            array_push($info, $deductedMinors);

            return view('userpages/report-template/all-barangay-status', 
                    compact('info', 'category', 'pwds', 
                    'barangay', 'year', 'totalPopulation', 'valueNewPwd', 'valueDeductedPwd', 
                    'addedPwd' ,'deductedPwd'));         

        }
        elseif($category == "status_category3"){

            $year =  (int)$request->quarterly_year;

            $totalNewPwd = 0;
            $totalDeductedPwd = 0;   

            $canceledQuarter1 = 0;
            $canceledQuarter2 = 0;
            $canceledQuarter3 = 0;
            $canceledQuarter4 = 0;

            $newPwdQuarter1 = 0;
            $newPwdQuarter2 = 0;
            $newPwdQuarter3 = 0;
            $newPwdQuarter4 = 0;

            $totalPopulationCountQuarter1 = 0;
            $totalPopulationCountQuarter2 = 0;
            $totalPopulationCountQuarter3 = 0;
            $totalPopulationCountQuarter4 = 0;

            foreach($pwds as $pwd){
                if(date('Y', strtotime($pwd->pwd_status->cancelled_date)) == $year){
                    if($pwd->sex == "Male"){
                        $deductedMale++;
                    }
                    if($pwd->age < 19){
                        $deductedMinors++;
                    }
                    $deductedPwd->push($pwd);
                    if(date('m', strtotime($pwd->pwd_status->cancelled_date)) <=  3 && false == (date('m', strtotime($pwd->pwd_status->cancelled_date)) >= 4)){
                        $canceledQuarter1++;
                    }
                    elseif(date('m', strtotime($pwd->pwd_status->cancelled_date)) <=  6 && false == (date('m', strtotime($pwd->pwd_status->cancelled_date)) >= 7)){
                        $canceledQuarter2++;
                    }
                    elseif(date('m', strtotime($pwd->pwd_status->cancelled_date)) <=  9 && false == (date('m', strtotime($pwd->pwd_status->cancelled_date)) >= 10)){
                        $canceledQuarter3++;
                    }
                    elseif(date('m', strtotime($pwd->pwd_status->cancelled_date)) <=  12 && false == (date('m', strtotime($pwd->pwd_status->cancelled_date)) >= 13)){
                        $canceledQuarter4++;
                    }
                }
            
                if(date('Y', strtotime($pwd->created_at)) == $year)  {
                    if($pwd->sex == "Male"){
                        $addedMale++;
                    }
                    if($pwd->age < 19){
                        $addedMinors++;
                    }
                    $addedPwd->push($pwd);

                    if(date('m', strtotime($pwd->created_at)) <=  3 && false == (date('m', strtotime($pwd->created_at)) >= 4)){
                        $newPwdQuarter1++;
                    }
                    elseif(date('m', strtotime($pwd->created_at)) <=  6 && false == (date('m', strtotime($pwd->created_at)) >= 7)){
                        $newPwdQuarter2++;
                    }
                    elseif(date('m', strtotime($pwd->created_at)) <=  9 && false == (date('m', strtotime($pwd->created_at)) >= 10)){
                        $newPwdQuarter3++;
                    }
                    elseif(date('m', strtotime($pwd->created_at)) <=  12 && false == (date('m', strtotime($pwd->created_at)) >= 13)){
                        $newPwdQuarter4++;
                    }
                }  

                    if(date('Y-m', strtotime($pwd->created_at)) < $year."-01"){
                        if($pwd->pwd_status->cancelled_date == null){
                            $totalPopulationCountQuarter1 ++;
                        }
                        elseif(date('Y-m', strtotime($pwd->pwd_status->cancelled_date)) >= $year."-01"){
                            $totalPopulationCountQuarter1 ++;
                        }
                    }
                    if(date('Y-m', strtotime($pwd->created_at)) < $year."-04"){
                        if($pwd->pwd_status->cancelled_date == null){
                            $totalPopulationCountQuarter2 ++;
                        }
                        elseif(date('Y-m', strtotime($pwd->pwd_status->cancelled_date)) >= $year."-04"){
                            $totalPopulationCountQuarter2 ++;
                        }
                    }
                    if(date('Y-m', strtotime($pwd->created_at)) < $year."-07"){
                        if($pwd->pwd_status->cancelled_date == null){
                            $totalPopulationCountQuarter3 ++;
                        }
                        elseif(date('Y-m', strtotime($pwd->pwd_status->cancelled_date)) >= $year."-07"){
                            $totalPopulationCountQuarter3 ++;
                        }
                    }
                    if(date('Y-m', strtotime($pwd->created_at)) < $year."-10"){
                        if($pwd->pwd_status->cancelled_date == null){
                            $totalPopulationCountQuarter4 ++;
                        }
                        elseif(date('Y-m', strtotime($pwd->pwd_status->cancelled_date)) >= $year."-10" ){
                            $totalPopulationCountQuarter4 ++;
                        }
                    }
                    
            }

            array_push($totalPopulation, $totalPopulationCountQuarter1);
            array_push($totalPopulation, $totalPopulationCountQuarter2);
            array_push($totalPopulation, $totalPopulationCountQuarter3);
            array_push($totalPopulation, $totalPopulationCountQuarter4);

            array_push($valueNewPwd, $newPwdQuarter1);
            array_push($valueNewPwd, $newPwdQuarter2);
            array_push($valueNewPwd, $newPwdQuarter3);
            array_push($valueNewPwd, $newPwdQuarter4);
                    
            array_push($valueDeductedPwd, $canceledQuarter1);
            array_push($valueDeductedPwd, $canceledQuarter2);
            array_push($valueDeductedPwd, $canceledQuarter3);
            array_push($valueDeductedPwd, $canceledQuarter4);

            array_push($info, $addedMale);
            array_push($info, $deductedMale);
            array_push($info, $addedMinors);
            array_push($info, $deductedMinors);

            return view('userpages/report-template/all-barangay-status', 
                    compact('info', 'category', 'pwds', 
                    'barangay', 'year', 'totalPopulation', 'valueNewPwd', 'valueDeductedPwd', 
                    'addedPwd' ,'deductedPwd'));  

        }
        else{

            $year = null;
            $dateStart = $request->date_start;
            $dateEnd =  $request->date_end;
            $totalNewPwd = 0;
            $totalDeductedPwd = 0;                    

            foreach($pwds as $pwd){
                
                if(date('Y-m-d', strtotime($pwd->pwd_status->cancelled_date)) >= $dateStart && date('Y-m-d', strtotime($pwd->pwd_status->cancelled_date)) <= $dateEnd){
                    $totalDeductedPwd++;
                }
                if(date('Y-m-d', strtotime($pwd->created_at)) >= $dateStart && date('Y-m-d', strtotime($pwd->created_at)) <= $dateEnd){
                    $totalNewPwd++;
                }
               
            }   

            array_push($valueNewPwd, $totalNewPwd);
            array_push($valueDeductedPwd, $totalDeductedPwd);

            $dateStart = date('M d, Y', strtotime($request->date_start));
            $dateEnd =  date('M d, Y', strtotime($request->date_end));

            return view('userpages/report-template/all-barangay-status', compact('category','statusDatas', 'pwds', 'barangay', 'year', 'valueNewPwd', 'valueDeductedPwd', 'dateStart' ,'dateEnd'));
            
        }

    }

    public function generateInitialProgramReports(Request $request, $id){
      
        if($request->barangay_id === "20"){
            $program = Program::with('pwd')->where('program_id', '=', $id)->first();
            $barangays = Barangay::all();
            $beneficiaryList = $program->pwd->sortBy('last_name');
          
        }else{
            $barangays = Barangay::where('barangay_id', $request->barangay_id)->get();
            $program = Program::with('pwd')->where('program_id', '=', $id)->first();
            $beneficiaryList = $program->pwd->where('barangay_id', '=', $request->barangay_id);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');        

        $pdf->loadView('userpages/report-template/program-initial-report', compact('beneficiaryList', 'program', 'barangays'));
    
        $pdf->render();

        return $pdf->download('Program Initial Report.pdf');

    }

    public function generateInitialAttendee($id){
       
        $program = Program::with('pwd')->where('program_id', '=', $id)->first();

        if(Auth::user()->user_role == null){
            $barangays = Barangay::all();
            $beneficiaryList = $program->pwd->sortBy('last_name');
        }
        else{
            $barangays = Barangay::where('barangay_id', Auth::user()->barangay_id);
            $beneficiaryList = $program->pwd->where('barangay_id', Auth::user()->barangay_id)->sortBy('last_name');
        }

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');        

        $pdf->loadView('userpages/report-template/initial-attendee-report', compact('beneficiaryList', 'program', 'barangays'));
    
        $pdf->render();

        return $pdf->download('Program Attendee Report.pdf');

    }

    public function generateInitialProgramReportsBarangay($id){

        $barangays = Barangay::where('barangay_id', Auth::user()->barangay_id)->get();
        $program = Program::with('pwd')->where('program_id', '=', $id)->first();
        $beneficiaryList = $program->pwd->where('barangay_id', '=', Auth::user()->barangay_id);

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/report-template/program-initial-report', compact('beneficiaryList', 'program', 'barangays'));
    
        $pdf->render();

        return $pdf->download('Program Beneficiary List.pdf');

    }

    public function generateClaimedReports(Program $program){
        
        $barangays = Barangay::where('barangay_id', Auth::user()->barangay_id)->first();

        $programPwd = Program::with('pwd')->where('program_id', $program->program_id)->first();

        $beneficiaryLists = $programPwd->pwd->where('barangay_id', Auth::user()->barangay_id);
        $beneficiaryList = $programPwd->pwd->where('barangay_id', Auth::user()->barangay_id)->count();

        $claimed = new Collection;
        $unClaimed = new Collection;

        foreach($beneficiaryLists as $pwd){
            if($pwd->pivot->is_unclaim == 0)
                $claimed->push($pwd);
            else
                $unClaimed->push($pwd);
        }
        
        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/report-template/program-report-summary', compact('programPwd', 'barangays', 'claimed', 'unClaimed' ,'beneficiaryLists' ));
    
        $pdf->render();

        return $pdf->download('Summary Report '. $barangays->barangay_name.'.pdf');
    }

    public function generateClaimedReportsOic(Request $request, Program $program){

        $claimed = new Collection;
        $unClaimed = new Collection;

        if($request->barangay_id == 20){
            
            $barangays = Barangay::all();

            $programPwd = Program::with('pwd')->where('program_id', $program->program_id)->first();

            $beneficiaryLists = $programPwd->pwd->sortBy('last_name');

            $beneficiaryLists->all();

            foreach($beneficiaryLists as $pwd){
                if($pwd->pivot->is_unclaim == 0)
                    $claimed->push($pwd);
                else
                    $unClaimed->push($pwd);

            }

        }else{

            $barangays = Barangay::where('barangay_id', $request->barangay_id)->get();

            $programPwd = Program::with('pwd')->where('program_id', $program->program_id)->first();

            $beneficiaryLists = $programPwd->pwd->where('barangay_id', $request->barangay_id);

            foreach($beneficiaryLists as $pwd){
                if($pwd->pivot->is_unclaim == 0)
                    $claimed->push($pwd);
                else
                    $unClaimed->push($pwd);

            }
        }
        
        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/report-template/program-report-summary', compact('programPwd', 'barangays', 'claimed', 'unClaimed' ,'beneficiaryLists' ));
    
        $pdf->render();

        if($request->barangay_id == 20){
            return $pdf->download('Summary Report '. 'All Barangays'.'.pdf');
        }
        else{
            return $pdf->download('Summary Report '. $barangays->first()->barangay_name.'.pdf');
        }
    }

    public function generateAppointmentReports(Request $request){

        $scheduled = new Collection;
        $walkIn = new Collection;

        $applicationValue = [];
        $renewalValue = [];
        $lostIDValue = [];
        $cancellationValue = [];

        $WalkInApplicationValue = [];
        $WalkInRenewalValue = [];
        $WalkInLostIDValue = [];
        $WalkInCancellationValue = [];

        $category = $request->category;

        if($request->barangay_id == 20){
            $applications = Appointment::where('appointment_status', 'Done')
                    ->where('transaction', 'Application')
                    ->get();
            $renewals = Appointment::where('appointment_status', 'Done')
                    ->where('transaction', 'Renewal ID')
                    ->get();

            $lostIDs = Appointment::where('appointment_status', 'Done')
                    ->where('transaction', 'Lost ID')
                    ->get(); 

            $cancellations = Appointment::where('appointment_status', 'Done')
                    ->where('transaction', 'Cancellation')
                    ->get();

            $walkInApplications = WalkIn::where('transaction', 'Application')->get();
            $walkInRenewalIDs = WalkIn::where('transaction', 'Renewal ID')->get();
            $walkInLostIDs = WalkIn::where('transaction', 'Lost ID')->get();
            $walkInCancellations = WalkIn::where('transaction', 'Cancellation')->get();    

        }
        else{
            $applications = Appointment::where('appointment_status', 'Done')
                    ->where('barangay_id', $request->barangay_id)
                    ->where('transaction', 'Application')
                    ->get();
            $renewals = Appointment::where('appointment_status', 'Done')
                    ->where('barangay_id', $request->barangay_id)
                    ->where('transaction', 'Renewal ID')
                    ->get();
            $lostIDs = Appointment::where('appointment_status', 'Done')
                    ->where('barangay_id', $request->barangay_id)
                    ->where('transaction', 'Lost ID')
                    ->get(); 
            $cancellations = Appointment::where('appointment_status', 'Done')
                    ->where('barangay_id', $request->barangay_id)
                    ->where('transaction', 'Cancellation')
                    ->get();
            $walkInApplications = WalkIn::where('transaction', 'Application')
                    ->where('barangay_id', $request->barangay_id)    
                    ->get();
            $walkInRenewalIDs = WalkIn::where('transaction', 'Renewal ID')
                    ->where('barangay_id', $request->barangay_id)    
                    ->get();
            $walkInLostIDs = WalkIn::where('transaction', 'Lost ID')
                    ->where('barangay_id', $request->barangay_id)    
                    ->get();
            $walkInCancellations = WalkIn::where('transaction', 'Cancellation')
                    ->where('barangay_id', $request->barangay_id)    
                    ->get();    
        }

        if($category == "Yearly"){

            if($request->appointment_start_year > $request->appointment_end_year){
                Alert::html('ERROR', 'Creating report unsuccessfully !', 'error');
                return back();
            }

            $year = [] ;

            $currentYear = (int)$request->appointment_start_year;    

            for($i = (int)$request->appointment_start_year; $i <= (int)$request->appointment_end_year; $i++){
                //appointment process
                $applicationCount = 0;
                $renewalCount = 0;
                $lostIDCount = 0;
                $cancellationCount = 0;

                foreach($applications as $application){
                    if(date('Y', strtotime($application->appointment_date)) == $currentYear)  {
                        $scheduled->push($application);
                        $applicationCount++;
                    }  
                }
                foreach($renewals as $renewal){
                    if(date('Y', strtotime($renewal->appointment_date)) == $currentYear)  {
                        $scheduled->push($renewal);
                        $renewalCount++;
                    }  
                }
                foreach($lostIDs as $lostID){
                    if(date('Y', strtotime($lostID->appointment_date)) == $currentYear)  {
                        $scheduled->push($lostID);
                        $lostIDCount++;
                    }  
                }
                foreach($cancellations as $cancellation){
                    if(date('Y', strtotime($cancellation->appointment_date)) == $currentYear)  {
                        $scheduled->push($cancellation);
                        $cancellationCount++;
                    }  
                }

                array_push($applicationValue, $applicationCount);
                array_push($renewalValue, $renewalCount);
                array_push($lostIDValue, $lostIDCount);
                array_push($cancellationValue, $cancellationCount);

                //Walk ins Transaction

                $walkInApplicationCount = 0;
                $walkInRenewalCount = 0;
                $walkInLostIDCount = 0;
                $walkInCancellationCount = 0;

                foreach($walkInApplications as $walkInApplication){
                    if(date('Y', strtotime($walkInApplication->created_at)) == $currentYear)  {
                        $walkIn->push($walkInApplication);
                        $walkInApplicationCount++;
                    }  
                }
                foreach($walkInRenewalIDs as $walkInRenewalID){
                    if(date('Y', strtotime($walkInRenewalID->created_at)) == $currentYear)  {
                        $walkIn->push($walkInRenewalID);
                        $walkInRenewalCount++;
                    }  
                }
                foreach($walkInLostIDs as $walkInLostID){
                    if(date('Y', strtotime($walkInLostID->created_at)) == $currentYear)  {
                        $walkIn->push($walkInLostID);
                        $walkInLostIDCount++;
                    }  
                }
                foreach($walkInCancellations as $cancellation){
                    if(date('Y', strtotime($cancellation->created_at)) == $currentYear)  {
                        $walkIn->push($cancellation);
                        $walkInCancellationCount++;
                    }  
                }

                array_push($WalkInApplicationValue, $walkInApplicationCount);
                array_push($WalkInRenewalValue, $walkInRenewalCount);
                array_push($WalkInLostIDValue, $walkInLostIDCount);
                array_push($WalkInCancellationValue, $walkInCancellationCount);

                array_push($year, $currentYear);
                $currentYear++;
            }
        }
        
        elseif($category == "Monthly"){

            $year =  (int)$request->monthly_year;

            for($i = 0; $i < 12; $i++){
                
                $applicationCount = 0;
                $renewalCount = 0;
                $lostIDCount = 0;
                $cancellationCount = 0;

                foreach($applications as $application){
                    if(date('Y', strtotime($application->appointment_date)) == $year)  {
                        if(date('m', strtotime($application->appointment_date)) == $i+1){
                            $scheduled->push($application);
                            $applicationCount++;
                        }
                    }  
                }
                foreach($renewals as $renewal){
                    if(date('Y', strtotime($renewal->appointment_date)) == $year)  {
                        if(date('m', strtotime($renewal->appointment_date)) == $i+1){
                            $scheduled->push($renewal);
                            $renewalCount++;
                        }
                    }  
                }
                foreach($lostIDs as $lostID){
                    if(date('Y', strtotime($lostID->appointment_date)) == $year)  {
                        if(date('m', strtotime($lostID->appointment_date)) == $i+1){
                            $scheduled->push($lostID);
                            $lostIDCount++;
                        }
                    }  
                }
                foreach($cancellations as $cancellation){
                    if(date('Y', strtotime($cancellation->appointment_date)) == $year)  {
                        if(date('m', strtotime($cancellation->appointment_date)) == $i+1){
                            $scheduled->push($cancellation);
                            $cancellationCount++;
                        }
                    }  
                }   

                array_push($applicationValue, $applicationCount);
                array_push($renewalValue, $renewalCount);
                array_push($lostIDValue, $lostIDCount);
                array_push($cancellationValue, $cancellationCount);

                //walkin process
                $walkInApplicationCount = 0;
                $walkInRenewalCount = 0;
                $walkInLostIDCount = 0;
                $walkInCancellationCount = 0;
                
                foreach($walkInApplications as $walkInApplication){
                    if(date('Y', strtotime($walkInApplication->created_at)) == $year)  {
                        if(date('m', strtotime($walkInApplication->created_at)) == $i+1){
                            $walkIn->push($walkInApplication);
                            $walkInApplicationCount++;
                        }
                    }  
                } 
                foreach($walkInRenewalIDs as $walkInRenewalID){
                    if(date('Y', strtotime($walkInRenewalID->created_at)) == $year)  {
                        if(date('m', strtotime($walkInRenewalID->created_at)) == $i+1){
                            $walkIn->push($walkInRenewalID);
                            $walkInRenewalCount++;
                        }
                    }  
                } 
                foreach($walkInLostIDs as $walkInLostID){
                    if(date('Y', strtotime($walkInLostID->created_at)) == $year)  {
                        if(date('m', strtotime($walkInLostID->created_at)) == $i+1){
                            $walkIn->push($walkInLostID);
                            $walkInLostIDCount++;
                        }
                    }  
                } 
                foreach($walkInCancellations as $walkInCancellation){
                    if(date('Y', strtotime($walkInCancellation->created_at)) == $year)  {
                        if(date('m', strtotime($walkInCancellation->created_at)) == $i+1){
                            $walkIn->push($walkInCancellation);
                            $walkInCancellationCount++;
                        }
                    }  
                } 

                array_push($WalkInApplicationValue, $walkInApplicationCount);
                array_push($WalkInRenewalValue, $walkInRenewalCount);
                array_push($WalkInLostIDValue, $walkInLostIDCount);
                array_push($WalkInCancellationValue, $walkInCancellationCount);
            }            
        }

        elseif($category == "Quarterly"){

            $year =  (int)$request->quarterly_year;

            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($applications as $application){
                if(date('Y', strtotime($application->appointment_date)) == $year){
                    $scheduled->push($application);
                    if(date('m', strtotime($application->appointment_date)) <=  3 && false == (date('m', strtotime($application->appointment_date)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($application->appointment_date)) <=  6 && false == (date('m', strtotime($application->appointment_date)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($application->appointment_date)) <=  9 && false == (date('m', strtotime($application->appointment_date)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($application->appointment_date)) <=  12 && false == (date('m', strtotime($application->appointment_date)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($applicationValue, $quarter1);
            array_push($applicationValue, $quarter2);
            array_push($applicationValue, $quarter3);
            array_push($applicationValue, $quarter4);

            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($renewals as $renewal){
                if(date('Y', strtotime($application->appointment_date)) == $year){
                    $scheduled->push($renewal);
                    if(date('m', strtotime($renewal->appointment_date)) <=  3 && false == (date('m', strtotime($renewal->appointment_date)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($renewal->appointment_date)) <=  6 && false == (date('m', strtotime($renewal->appointment_date)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($renewal->appointment_date)) <=  9 && false == (date('m', strtotime($renewal->appointment_date)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($renewal->appointment_date)) <=  12 && false == (date('m', strtotime($renewal->appointment_date)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($renewalValue, $quarter1);
            array_push($renewalValue, $quarter2);
            array_push($renewalValue, $quarter3);
            array_push($renewalValue, $quarter4);

            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($lostIDs as $lostID){
                if(date('Y', strtotime($lostID->appointment_date)) == $year){
                    $scheduled->push($lostID);
                    if(date('m', strtotime($lostID->appointment_date)) <=  3 && false == (date('m', strtotime($lostID->appointment_date)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($lostID->appointment_date)) <=  6 && false == (date('m', strtotime($lostID->appointment_date)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($lostID->appointment_date)) <=  9 && false == (date('m', strtotime($lostID->appointment_date)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($lostID->appointment_date)) <=  12 && false == (date('m', strtotime($lostID->appointment_date)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($lostIDValue, $quarter1);
            array_push($lostIDValue, $quarter2);
            array_push($lostIDValue, $quarter3);
            array_push($lostIDValue, $quarter4);
            
            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($cancellations as $cancellation){
                if(date('Y', strtotime($cancellation->appointment_date)) == $year){
                    $scheduled->push($cancellation);
                    if(date('m', strtotime($cancellation->appointment_date)) <=  3 && false == (date('m', strtotime($cancellation->appointment_date)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($cancellation->appointment_date)) <=  6 && false == (date('m', strtotime($cancellation->appointment_date)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($cancellation->appointment_date)) <=  9 && false == (date('m', strtotime($cancellation->appointment_date)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($cancellation->appointment_date)) <=  12 && false == (date('m', strtotime($cancellation->appointment_date)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($cancellationValue, $quarter1);
            array_push($cancellationValue, $quarter2);
            array_push($cancellationValue, $quarter3);
            array_push($cancellationValue, $quarter4);

            //Walkins process

            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($walkInApplications as $walkInApplication){
                if(date('Y', strtotime($walkInApplication->created_at)) == $year){
                    $walkIn->push($walkInApplication);
                    if(date('m', strtotime($walkInApplication->created_at)) <=  3 && false == (date('m', strtotime($walkInApplication->created_at)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($walkInApplication->created_at)) <=  6 && false == (date('m', strtotime($walkInApplication->created_at)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($walkInApplication->created_at)) <=  9 && false == (date('m', strtotime($walkInApplication->created_at)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($walkInApplication->created_at)) <=  12 && false == (date('m', strtotime($walkInApplication->created_at)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($WalkInApplicationValue, $quarter1);
            array_push($WalkInApplicationValue, $quarter2);
            array_push($WalkInApplicationValue, $quarter3);
            array_push($WalkInApplicationValue, $quarter4);

            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($walkInRenewalIDs as $walkInRenewalID){
                if(date('Y', strtotime($walkInRenewalID->created_at)) == $year){
                    $walkIn->push($walkInRenewalID);
                    if(date('m', strtotime($walkInRenewalID->created_at)) <=  3 && false == (date('m', strtotime($walkInRenewalID->created_at)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($walkInRenewalID->created_at)) <=  6 && false == (date('m', strtotime($walkInRenewalID->created_at)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($walkInRenewalID->created_at)) <=  9 && false == (date('m', strtotime($walkInRenewalID->created_at)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($walkInRenewalID->created_at)) <=  12 && false == (date('m', strtotime($walkInRenewalID->created_at)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($WalkInRenewalValue, $quarter1);
            array_push($WalkInRenewalValue, $quarter2);
            array_push($WalkInRenewalValue, $quarter3);
            array_push($WalkInRenewalValue, $quarter4);

            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($walkInLostIDs as $walkInLostID){
                if(date('Y', strtotime($walkInLostID->created_at)) == $year){
                    $walkIn->push($walkInLostID);
                    if(date('m', strtotime($walkInLostID->created_at)) <=  3 && false == (date('m', strtotime($walkInLostID->created_at)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($walkInLostID->created_at)) <=  6 && false == (date('m', strtotime($walkInLostID->created_at)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($walkInLostID->created_at)) <=  9 && false == (date('m', strtotime($walkInLostID->created_at)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($walkInLostID->created_at)) <=  12 && false == (date('m', strtotime($walkInLostID->created_at)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($WalkInLostIDValue, $quarter1);
            array_push($WalkInLostIDValue, $quarter2);
            array_push($WalkInLostIDValue, $quarter3);
            array_push($WalkInLostIDValue, $quarter4);

            $quarter1 = 0;
            $quarter2 = 0;
            $quarter3 = 0;
            $quarter4 = 0;

            foreach($walkInCancellations as $walkInCancellation){
                if(date('Y', strtotime($walkInCancellation->created_at)) == $year){
                    $walkIn->push($walkInCancellation);
                    if(date('m', strtotime($walkInCancellation->created_at)) <=  3 && false == (date('m', strtotime($walkInCancellation->created_at)) >= 4)){
                        $quarter1++;
                    }
                    elseif(date('m', strtotime($walkInCancellation->created_at)) <=  6 && false == (date('m', strtotime($walkInCancellation->created_at)) >= 7)){
                        $quarter2++;
                    }
                    elseif(date('m', strtotime($walkInCancellation->created_at)) <=  9 && false == (date('m', strtotime($walkInCancellation->created_at)) >= 10)){
                        $quarter3++;
                    }
                    elseif(date('m', strtotime($walkInCancellation->created_at)) <=  12 && false == (date('m', strtotime($walkInCancellation->created_at)) >= 13)){
                        $quarter4++;
                    }
                }         
            }

            array_push($WalkInCancellationValue, $quarter1);
            array_push($WalkInCancellationValue, $quarter2);
            array_push($WalkInCancellationValue, $quarter3);
            array_push($WalkInCancellationValue, $quarter4);

        }
        else{

            $year = "(All Times)";

            $scheduled = Appointment::where('appointment_status', 'Done')->get();

            $walkIn = WalkIn::all();
            
            array_push($applicationValue, $applications->count());
            array_push($renewalValue, $renewals->count());
            array_push($lostIDValue, $lostIDs->count());
            array_push($cancellationValue, $cancellations->count());

            array_push($WalkInApplicationValue, $walkInApplications->count());
            array_push($WalkInRenewalValue, $walkInRenewalIDs->count());
            array_push($WalkInLostIDValue, $walkInLostIDs->count());
            array_push($WalkInCancellationValue, $walkInCancellations->count());

        }

        return view('userpages/report-template/appointment-graph', 
                    compact('category', 'year', 'scheduled', 'walkIn', 'applicationValue', 
                    'renewalValue', 'lostIDValue','cancellationValue',
                    'WalkInApplicationValue', 'WalkInRenewalValue','WalkInLostIDValue',
                    'WalkInCancellationValue'
                ));
    }

    public function generateDisabilityReport(Request $request){

        if(Auth::user()->user_role == 2){
            $request->validate([
                'disability_name' => 'required',
                'barangay_id' => 'required'
            ]);

            if($request->barangay_id == 20 ){
                $barangay = "All Barangays";
                $selectDisabilty = Pwd::where('disability_name', strtoupper($request->disability_name))->get();
            }
            else{
                $barangay = Barangay::where('barangay_id', $request->barangay_id)->first()->barangay_name;
                $selectDisabilty = Pwd::where('disability_name', strtoupper($request->disability_name))
                                    ->where('barangay_id', $request->barangay_id)                    
                                    ->get();
            }
        }
        else{

            $request->validate([
                'disability_name' => 'required'
            ]);

            $barangay = Barangay::where('barangay_id', Auth::user()->barangay_id )->first()->barangay_name;
            $selectDisabilty = Pwd::where('disability_name', strtoupper($request->disability_name))
                            ->where('barangay_id', Auth::user()->barangay_id)                    
                            ->get();

        }
       
        $selectedPwd;
        $disabilityName = $request->disability_name;

        if($request->min_age != 0 || $request->max_age  != 0){
            $age = $request->min_age ." - ". $request->max_age . ' years-old ';
            $selectAgeMax =  $selectDisabilty->where('age', '<=', $request->max_age);
            $selectAgeMin =  $selectAgeMax->where('age', '>=', $request->min_age);
            $selectedPwd = $selectAgeMin;
        }
        else{
            $age = "None";
            $selectedPwd =  $selectDisabilty;
        }

        if($request->sex != null){
            $selectGender = $selectedPwd->where('sex', $request->sex);
            $selectedPwd = $selectGender;
            $sex = $request->sex;
        }
        else{
            $sex = "None";
        }
        
        $valueNewPwd = [];
        $valueDeductedPwd = [];

        $activePwd = new Collection;
        $inActivePwd = new Collection;
        $cancelledPwd = new Collection;

        foreach ($selectedPwd as $pwd){

            if($pwd->pwd_status->id_expiration > date('Y-m-d') && $pwd->pwd_status->cancelled == 0){
                $activePwd->push($pwd);
            }
            elseif($pwd->pwd_status->cancelled == 0){
                $inActivePwd->push($pwd);
            }
            else{
                $cancelledPwd->push($pwd);
            }
            
        }

        if($request->name_list == null){
            $name_list = false;
        }
        else{
            $name_list = true;
        }
    
        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('userpages/report-template/disability-report-template', compact('disabilityName', 'sex' , 'age', 'barangay' , 'inActivePwd' , 'cancelledPwd' ,'activePwd' , 'name_list'));
        $pdf->render();
        
        return $pdf->download('Disability Reports '.$request->disability_name.' '. $barangay .'.pdf');  
    }

}
