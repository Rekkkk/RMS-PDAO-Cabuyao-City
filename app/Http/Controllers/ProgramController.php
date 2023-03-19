<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pwd;
use App\Models\Program;
use App\Models\ProgramImage;
use App\Models\ProgramStatus;
use App\Models\ProgramClaimant;
use App\Models\ProgramMemo;
use App\Models\ProgramSignatory;
use App\Models\ProgramBeneficiaryDetails;
use App\Models\Barangay;
use App\Models\ActivityLog;
use Illuminate\Support\Collection;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;
use Image;
use Storage;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Mail;
use App\Mail\ProgramMail;


class ProgramController extends Controller
{
    //guest acess

    public function pwdPrograms(){

        $programs = Program::latest("program_id")->get();

        return view('landingpage/landingpages/programs/programs', compact('programs'));
    }

    public function guestViewProgram($id){

        $selectedProgram = Program::find($id);

        return view('landingpage/landingpages/programs/view-program', compact('selectedProgram'));
    }

    public function programManagement($sortBy){

        if($sortBy === 'A-Z'){
            $programs = Program::all()->sortBy('program_title');
        }
        if($sortBy === 'Date'){
            $programs = Program::all()->sortByDesc('created_at');
            // $programs = Program::sortBy('program_title', 'asc')->get();
            
        }
        
        return view('userpages/programs/program-management', compact('programs'));
    }

    public function createProgram(){

        return view('userpages/programs/create-program');
    }

    public function createProgramSave(Request $request){

        $request->validate([
            'program_title' => 'required',
            'program_type' => 'required',
            'barangay_id' => 'required',
            'disability_involve' => 'required',
            'program_description' => 'required',
        ]);

        if($request->cash_amount != null){
            if($request->cash_amount < 0){
                Alert::html('Error', 'You cannot put negative amount !','error');
                return back()->withInput();
            }
        }

        Program::create([
            'program_title' => $request->program_title,
            'program_type' => $request->program_type,
            'cash_amount' => $request->cash_amount,
            'barangay_id' => $request->barangay_id,
            'disability_involve' => $request->disability_involve,
            'program_description' => $request->program_description
        ]);

        ProgramStatus::create([
            'program_id' => Program::all()->last()->program_id,
            'encoding_status' => 0,
            'is_done' => 0,
        ]);

        if($request->images != null){
            foreach ($request->file('images') as $imagefile) {

                $image = new ProgramImage;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->program_id = Program::all()->last()->program_id;
                $image->save();
            }
        }

        foreach ($request->file('memo') as $imagefile) {
            $image = new ProgramMemo;
            $path = $imagefile->store('/', ['disk' =>   'images']);
            $image->img_name = $path;
            $image->program_id = Program::all()->last()->program_id;
            $image->save();
        }

        $program = Program::all()->last();

        if($request->barangay_id == 1){
            $pwdList = Pwd::all();
            foreach($pwdList as $pwd){  
                if($pwd->pwd_status->id_expiration > date('Y-m-d') && $pwd->pwd_status->cancelled == 0){
                    if($request->disability_involve == "All Disabilities"){                        
                        ProgramClaimant::create([
                            'pwd_id' => $pwd->pwd_id,
                            'program_id' => $program->program_id,
                            'is_unclaim' => 0
                        ]); 
                    }
                    else{
                        if($pwd->disability_type == $request->disability_involve){
                            ProgramClaimant::create([
                                'pwd_id' => $pwd->pwd_id,
                                'program_id' => $program->program_id,
                                'is_unclaim' => 0
                            ]);
                        }
                    }
                }
            }
        }
        else{
            $pwdList = Pwd::where('barangay_id', $request->barangay_id)->get();
            foreach($pwdList as $pwd){
                if($pwd->pwd_status->id_expiration > date('Y-m-d') && $pwd->pwd_status->cancelled == 0){
                    if($request->disability_involve == "All Disabilities"){
                        ProgramClaimant::create([
                            'pwd_id' => $pwd->pwd_id,
                            'program_id' => $program->program_id,
                            'is_unclaim' => 0
                        ]); 
                    }
                    else{
                        if($pwd->disability_type == $request->disability_involve){
                            ProgramClaimant::create([
                                'pwd_id' => $pwd->pwd_id,
                                'program_id' => $program->program_id,
                                'is_unclaim' => 0
                            ]);
                        }
                    }
                }
            }
        }

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User created new PWD program named " . $request->program_title
        ]); 

        Alert::success('Success', 'Program added Successfully.');

        return redirect()->route('program.management', 'A-Z');
     
    }

    public function emailPWD(Program $program){

        if($program->barangay_id == 1){
            $pwdList = Pwd::all();
            foreach($pwdList as $pwd){  
                if($pwd->pwd_status->id_expiration > date('Y-m-d') && $pwd->pwd_status->cancelled == 0){
                    if($program->disability_involve == "All Disabilities"){
                        Mail::to($pwd->email)->send(new ProgramMail($program, $pwd));
                    }
                    else{
                        if($pwd->disability_type == $program->disability_involve){
                            Mail::to($pwd->email)->send(new ProgramMail($program, $pwd));
                        }
                    }
                }
            }
        }
        else{
            $pwdList = Pwd::where('barangay_id', $program->barangay_id)->get();
            foreach($pwdList as $pwd){
                if($pwd->pwd_status->id_expiration > date('Y-m-d') && $pwd->pwd_status->cancelled == 0){
                    if($program->disability_involve == "All Disabilities"){
                        Mail::to($pwd->email)->send(new ProgramMail($program, $pwd));
                    }
                    else{
                        if($pwd->disability_type == $program->disability_involve){
                            Mail::to($pwd->email)->send(new ProgramMail($program, $pwd));  
                        }
                    }
                }
            }
        }

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User Send Emails to PWD's for Program " . $program->program_title
        ]); 

        Alert::success('Success', 'Email Send Successfully !');
        return back();
    }

    public function setEncoding(Program $program){

        if($program->programStatus->is_done == 1){
            Alert::html('Error', 'Program Mark as done !', 'error');
            return back();
        }

        if($program->programStatus->encoding_status == 0){
            ProgramStatus::where('program_id', $program->program_id)
            ->update(['encoding_status' => 1]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User opened encoding for " . $program->program_title . " Program"
            ]); 

        }else{
            ProgramStatus::where('program_id', $program->program_id)
            ->update(['encoding_status' => 0]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User closed encoding for " . $program->program_title . " Program"
            ]); 
        }

        Alert::success('Success', 'Encoding Status set Successfully.');

        return back();

    }

    public function markAsDone(Program $program){

        if($program->programStatus->is_done == 0){
            if($program->programStatus->encoding_status == 1){
                ProgramStatus::where('program_id', $program->program_id)
                ->update(['is_done' => 1, 'encoding_status' => 0]);

            }else{
                ProgramStatus::where('program_id', $program->program_id)
                ->update(['is_done' => 1]);
            }
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User mark as done for " . $program->program_title . " Program"
            ]);

        }else{
            ProgramStatus::where('program_id', $program->program_id)
            ->update(['is_done' => 0]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User mark as Undone for " . $program->program_title . " Program"
            ]);
        }

        Alert::success('Success', 'Program Status set Successfully !');

        return back();
    }

    public function viewProgram($id){

        $program = Program::where('program_id', '=', $id)->first();

        return view('userpages/programs/view-program', compact('program'));
    }

    public function editProgram(Program $program){
        
        return view('userpages/programs/edit-program', compact('program'));

    }

    public function editProgramSave(Request $request, Program $program){

        if($request->images != null){
            foreach ($request->file('images') as $imagefile) {
                $image = new ProgramImage;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->program_id = $program->program_id;
                $image->save();
            }
        }

        if($request->memo != null){
            foreach ($request->file('memo') as $imagefile) {
                $image = new ProgramMemo;
                $path = $imagefile->store('/', ['disk' =>   'images']);
                $image->img_name = $path;
                $image->program_id = $program->program_id;
                $image->save();
            }
        }

       $beneficiaries = ProgramClaimant::where('program_id', $program->program_id)->get();
       
       foreach($beneficiaries as $beneficiary){
            $beneficiary->delete();
       }

       $program->update($request->all());

        if($program->barangay_id == 1){
            
            $pwdList = Pwd::all();
            foreach($pwdList as $pwd){  
                if($pwd->pwd_status->id_expiration > date('Y-m-d') && $pwd->pwd_status->cancelled == 0){
                    if($program->disability_involve == "All Disabilities"){             
                        ProgramClaimant::create([
                            'pwd_id' => $pwd->pwd_id,
                            'program_id' => $program->program_id,
                            'is_unclaim' => 0
                        ]); 
                    }
                    else{
                        if($pwd->disability_type == $program->disability_involve){
                            ProgramClaimant::create([
                                'pwd_id' => $pwd->pwd_id,
                                'program_id' => $program->program_id,
                                'is_unclaim' => 0
                            ]);
                        }
                    }
                }
            }
        }
        else{
            $pwdList = Pwd::where('barangay_id', $program->barangay_id)->get();
            foreach($pwdList as $pwd){
                if($pwd->pwd_status->id_expiration > date('Y-m-d') && $pwd->pwd_status->cancelled == 0){
                    if($program->disability_involve == "All Disabilities"){
                        ProgramClaimant::create([
                            'pwd_id' => $pwd->pwd_id,
                            'program_id' => $program->program_id,
                            'is_unclaim' => 0
                        ]); 
                    }
                    else{
                        if($pwd->disability_type == $program->disability_involve){
                            ProgramClaimant::create([
                                'pwd_id' => $pwd->pwd_id,
                                'program_id' => $program->program_id,
                                'is_unclaim' => 0
                            ]);
                        }
                    }
                }
            }
        }

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User edit program details for " . $program->program_title . " Program"
        ]); 

        Alert::success('Success', 'Program Edit Successfully !');

        return back();

    }

    public function deletePicture($picture){

        $image = ProgramImage::find($picture);
        $filename = public_path("/images/$image->img_name");

        if(File::exists(  $filename)) {
            File::delete($filename);
            $image->delete();
        }

        $program = Program::where('program_id',$image->program_id)->first();

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User deleted photo for " . $program->program_title . " Program"
        ]); 

        Alert::success('Success', 'Image Deleted !');
        return back();
    }

    public function deleteMemo($memorandum){

        $image = ProgramMemo::find($memorandum);
        $filename = public_path("/images/$image->img_name");

        if(File::exists(  $filename)) {
            File::delete($filename);
            $image->delete();
        }

        $program = Program::where('program_id',$image->program_id)->first();

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User deleted photo for " . $program->program_title . " Program"
        ]); 

        Alert::success('Success', 'Memorandum Deleted !');
        return back();
    }

    public function viewBeneficiariesList($id){

        $program = Program::with('pwd')->where('program_id', $id)->first();

        if(Auth::user()->user_role == 2){

            $barangays = Barangay::all();
            $beneficiaryList = $program->pwd;
            $signatory = ProgramSignatory::where('program_id', $program->program_id)->get();
            
            return view('userpages/programs/view-claim-benificiaries-oic', compact('beneficiaryList', 'program', 'barangays', 'signatory'));
        }
        else{

            $beneficiaryList = $program->pwd->where('barangay_id', Auth::user()->barangay_id);
            $signatory = ProgramSignatory::where('barangay_id', Auth::user()->barangay_id)->where('program_id', $program->program_id)->get();

            return view('userpages/programs/view-claim-beneficiaries', compact('beneficiaryList', 'program', 'signatory'));
        }

    }

    public function removeBeneficiary(Pwd $pwd, $program){

        $selectedProgram = Program::find($program);

        ProgramClaimant::where('program_id', $program)
                        ->where('pwd_id', $pwd->pwd_id)
                        ->delete();

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User removed " . $pwd->first_name ." " . $pwd->last_name. " as benificiary from " .$selectedProgram->program_title . " Program"
        ]); 

        Alert::success('Success', 'Remove beneficiary Successfully !');

        return back();

    }

    public function BeneficiariesListPrintOIC(Request $request, $id){

        if($request->barangay_id == null){
            Alert::html('ERROR', 'Please Select Barangay !', 'error');
            return back();
        }

        $barangays = Barangay::where('barangay_id', $request->barangay_id)->first();
        $program = Program::with('pwd')->where('program_id', $id)->first();
        $beneficiaryList = $program->pwd->where('barangay_id', $request->barangay_id)->sortBy('last_name');

        $html = '
        <style>
            table{
                border-collapse: collapse;
                width: 100%;
            }
            td, th{
                border:1px solid #444;
                padding: 5px;
            }
            
        </style>
       
        <h1>'.$program->program_title.'</h1>
        <h2> Brgy. '.$barangays->barangay_name.'</h2>
            <table>
    
                    <th>PWD ID</th>
                    <th>Name</th>
                    <th>Signature</th>
        
                ';
                foreach($beneficiaryList as $pwd){
                    $html .= '
                    <tr>
                        <td>'.$pwd->pwd_number.'</td>
                        <td>'.$pwd->last_name. ', ' . $pwd->first_name. ' ' .$pwd->middle_name. '</td>
                        <td></td>
                    </tr>
                ';
        }
        $dompdf = new Dompdf;
        $dompdf->loadHTML($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('Brgy. '.$barangays->barangay_name .'beneficiryList.pdf');
    }

    public function BeneficiariesList($id){
        $program = Program::with('pwd')->where('program_id', $id)->first();
        $beneficiaryList = $program->pwd->where('barangay_id', Auth::user()->barangay_id)->sortBy('last_name');;
        $barangays = Barangay::where('barangay_id', Auth::user()->barangay_id)->first();
        
        $html = '
        <style>
            table{
                border-collapse: collapse;
                width: 100%;
                height: 100px;
            }
        

            .border-table{
                border:1px solid #444;
                padding: 5px;
            }
        </style>
        <table>
        <tr>
            <td width="70%" style="word-wrap:break-word">
                <h2>'.$program->program_title.'</h2>
                <p style="margin-top: -15px;"> <b>Brgy. '.$barangays->barangay_name.'</b></p>
                <p style="margin-top: -15px;"> <b>Date Created</b> : '. date(' F d, Y') .'</p>
            </td>
            <td width="30%">
                
                <p style="margin-top: -15px; text-align: center;">'. Auth::user()->last_name ." ". Auth::user()->first_name  .'</b></p>
                <p style="margin-top: -15px; text-align: center;"><b>Barangay President</b></p>
            </td>
        </tr>
        </table>
            <table>
                <thead>
                    <tr>
                        <th class="border-table" >PWD ID</th>
                        <th class="border-table" >Name</th>
                        <th class="border-table" >Signature</th>
                    </tr>
                </thead>
                <tbody>
                ';
                foreach($beneficiaryList as $pwd){
                    $html .= '
                    <tr>
                        <td class="border-table">'.$pwd->pwd_number.'</td>
                        <td class="border-table">'.$pwd->last_name. ', ' . $pwd->first_name. ' ' .$pwd->middle_name. '</td>
                        <td class="border-table"></td>
                    </tr>
                ';
                }

        $dompdf = new Dompdf;
        $dompdf->loadHTML($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('beneficiary-list ' . $barangays->barangay_name.'.pdf');
    
    }

    public function printUnclaimList($id){

        $program = Program::with('pwd')->where('program_id', '=', $id)->first();
        $beneficiaryList = $program->pwd;
        
        $barangays = Barangay::where('barangay_id', Auth::user()->barangay_id)->first();

        $unclaimed = new Collection;
        
        $html = '
        <style>
            table{
                border-collapse: collapse;
                width: 100%;
            }
            td, th{
                border:1px solid #444;
                padding: 5px;
            }
            
        </style>
       
        <h1>'.$program->program_title.' (Unclaimed) </h1>
        <h2> Brgy. '.$barangays->barangay_name.'</h2>
            <table>
                
                    <tr>
                        <th>PWD ID</th>
                        <th>Name</th>
                     
                    </tr>
             
                
                ';
                foreach($beneficiaryList as $pwd){
                    if( $pwd->pivot->is_unclaim == 1){
                    $html .= '
                    <tr>
                        <td>'.$pwd->pwd_number.'</td>
                        <td>'.$pwd->last_name. ', ' . $pwd->first_name. ' ' .$pwd->middle_name. '</td>
                     
                    </tr>
                ';}
        }

        $dompdf = new Dompdf;
        $dompdf->loadHTML($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('Unclaim List '.  $barangays->barangay_name . '.pdf');
    
    }

    public function programBeneficiaryDetails($id){

        $program = Program::with('pwd')->where('program_id', '=', $id)->first();

        if($program->programStatus->encoding_status == 0){
            
            Alert::html('ERROR','Encoding not available !', 'error');
            return back();
        }

        $beneficiaryList = $program->pwd->where('barangay_id', Auth::user()->barangay_id);

        if($program->program_type == "Cash Gifts or Grocery Packs"){
            return view('userpages/programs/encode-claim-program', compact('beneficiaryList', 'program'));
        }
        else{
            return view('userpages/programs/encode-attendee-program', compact('beneficiaryList', 'program'));
        }
        
    }

    public function isClaim(Pwd $pwd, Program $program){

        $program = Program::with('pwd')->where('program_id', '=', $program->program_id)->first();


        $pwdTarget = $program->pwd->where('pwd_id', $pwd->pwd_id)->first();

        if($pwdTarget->pivot->is_unclaim == 1){
            ProgramClaimant::where('program_id', $program->program_id)
                            ->where('pwd_id', $pwd->pwd_id)
                            ->update(['is_unclaim' => 0]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User set " . $pwd->first_name ." " . $pwd->last_name. " as unclaimed benefits for " .$program->program_title . " Program"
            ]);                 
        }else{
            ProgramClaimant::where('program_id', $program->program_id)
                            ->where('pwd_id', $pwd->pwd_id)
                            ->update(['is_unclaim' => 1]);

            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User set " . $pwd->first_name ." " . $pwd->last_name. " as claimed benefits for " .$program->program_title . " Program"
            ]);                 
        }

        return back();
    }

    public function addPwdBeneficiary(Request $request, Program $program){
        $pwd = Pwd::where('pwd_number', $request->pwd_id)->first();

        if($pwd){

            $alreadyAdded = ProgramClaimant::where('program_id', '=' , $program->program_id)
            ->where('pwd_id', '=', $pwd->pwd_id)
            ->first();

            if(Auth::user()->barangay_id != $pwd->barangay_id && Auth::user()->user_role != 2){
                
                Alert::html('Success','PWD added unsuccessfully !', 'error');
                return back();
            }
            else if($alreadyAdded){
                Alert::html('Error','PWD already added !', 'error');
                return back();
            }
            else{
                ProgramClaimant::create([
                    'pwd_id' => $pwd->pwd_id,
                    'program_id' => $program->program_id,
                    'is_unclaim' => 0
                ]);  

                ActivityLog::create([
                    'user_id' => Auth::user()->user_id,
                    'eventdetails' => "User added " . $pwd->first_name ." " . $pwd->last_name. " as beneficiary for " .$program->program_title . " Program"
                ]);  

                Alert::success('Success','PWD added Successfully !');
                return back();
            }
  
        }else{
            Alert::html('Error','PWD ID Not Exist !', 'error');
            return back();
        }
    }

    public function uploadSignatory(Request $request, Barangay $barangay, Program $program){

        foreach ($request->file('signatory') as $pdfFile) {

            $pdf = new ProgramSignatory;
            $pdf->barangay_id = $barangay->barangay_id;
            $pdf->program_id = $program->program_id;
            $path = $pdfFile->store('/', ['disk' =>   'signatory']);
            $pdf->file_name = $path;
            $pdf->save();
        }

            Alert::success('Success', 'Signatory Upload Success !');

            return back();

    }

}
