<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barangay;
use App\Models\BarangayHandle;
use App\Models\UserStatus;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Dompdf\Dompdf;
use Carbon\Carbon;
use mikehaertl\wkhtmlto\Pdf;
use Validator;
use App;


class AccountManagementController extends Controller
{

    public function accountManagementPage(){
        
        if(Auth::user()->user_role == 2){
            $users = User::where('user_id', '!=', Auth::user()->user_id)
            ->where('user_role', '!=', 2)
            ->where('user_role', '!=', 3)
            ->get();
        }else{
            $users = User::where('user_role', 2)->get();
        }

        return view('userpages/accountmanagement/account-management', compact('users'));
    }

    public function createNewAccountPage(){
        $barangays= Barangay::all();

        return view('userpages/accountmanagement/create-account', compact('barangays'));
    }

    public function createNewAccount(Request $request){

        $chars      = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_?,.";
        $rand_chars = substr( str_shuffle( $chars ), 0, 12 );

        $password =  $rand_chars;

        if(Auth::user()->user_role == 3){

            $request->validate([
                'last_name' => 'required',
                'first_name' => 'required',
                'birthday' => 'required', 
                'sex' => 'required', 
                'civil_status' => 'required', 
                'address' => 'required', 
                'phone_number' => 'required',
            ]);

            $email = strtolower(str_replace(' ', '', $request->last_name. $request->first_name.$request->suffix .date('d', strtotime($request->birthday)) ."@pwdoic.com"));

            User::create([
                'user_id' => User::all()->last()->user_id,
                'user_role' => 2,
                'barangay_id' => null,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'suffix' => $request->suffix,
                'birthday' => $request->birthday,
                'sex' => $request->sex,
                'civil_status' => $request->civil_status,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'email' =>  $email,
                'password' => Hash::make($password),
                'temp_password' => $password,
            ]);

            UserStatus::create([
                'user_id' => User::all()->last()->user_id,
                'is_disable' => '0',
                'is_suspend' => '0'
            ]);

            Alert::success('Message', 'Account added successfully.');

            return redirect()->route('view.oic.account', User::all()->last()->user_id);
            
        }
        else{

            $request->validate([
                'last_name' => 'required',
                'first_name' => 'required',
                'birthday' => 'required', 
                'sex' => 'required', 
                'civil_status' => 'required', 
                'address' => 'required', 
                'phone_number' => 'required',
                'barangay_id' => 'required'
            ],[
                'barangay_id.required'=> 'The assign barangay field is required.'
            ]);

            $email = strtolower(str_replace(' ', '', $request->last_name. $request->first_name .  date('d', strtotime($request->birthday)) . "@pwd.com"));

            if( $request->barangay_id == 1){
                User::create([
                    'user_id' => User::all()->last()->user_id,
                    'user_role' => '0',
                    'barangay_id' => $request->barangay_id,
                    'last_name' => $request->last_name,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'suffix' => $request->suffix,
                    'birthday' => $request->birthday,
                    'sex' => $request->sex,
                    'civil_status' => $request->civil_status,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'email' =>  $email,
                    'password' => Hash::make($password),
                    'temp_password' => $password,
                ]);
            
                UserStatus::create([
                    'user_id' => User::all()->last()->user_id,
                    'is_disable' => '0',
                    'is_suspend' => '0'
                ]);
    
            }else{
                User::create([
                    'user_id' => User::all()->last()->user_id,
                    'user_role' => '1',
                    'barangay_id' => $request->barangay_id,
                    'last_name' => $request->last_name,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'suffix' => $request->suffix,
                    'birthday' => $request->birthday,
                    'sex' => $request->sex,
                    'civil_status' => $request->civil_status,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'email' =>  $email,
                    'password' => Hash::make($password),
                    'temp_password' => $password,
                ]);
            
                UserStatus::create([
                    'user_id' => User::all()->last()->user_id,
                    'is_disable' => '0',
                    'is_suspend' => '0'
                ]);
            }

            if($request->barangay_id == 1){
                ActivityLog::create([
                    'user_id' => Auth::user()->user_id,
                    'eventdetails' => "User add new office staff account."
                ]);
            }
                
            else{
                ActivityLog::create([
                    'user_id' => Auth::user()->user_id,
                    'eventdetails' => "User add new barangay president account for " . Barangay::where('barangay_id', $request->barangay_id)->first()->barangay_name . "."
                ]);
            }
            
            Alert::success('Message', 'Account added successfully.');

            return redirect()->route('view.account', User::all()->last()->user_id);
        }

    }

    public function viewAccount($id){

        $user = User::with('barangay')->with('userStatus')->where('user_id', '=', $id)->first();
    
        return view('userpages/accountmanagement/view-account', compact('user'));
        
    }

    public function dowloadAuth(User $user){

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');

        $pdf->loadView('userpages/accountmanagement/auth-dowload', compact('user'));
    
        $pdf->render();

        return $pdf->download($user->last_name.' New account.pdf');


    }

    public function suspendAccount(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'suspend_start' => 'required',
            'suspend_end' => 'required',
        ]);

        if ($validator->fails()) {
            Alert::html('ERROR', 'Suspend account unsuccessful !', 'error');
            
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if($request->suspend_start < Carbon::now() || $request->suspend_end < Carbon::now() || $request->suspend_start > $request->suspend_end){
            Alert::html('ERROR', 'Please do not select past and current date to starting date and to ending date must higher to starting date!', 'error');
            return back()->withInput();
        }

        $users = UserStatus::where('user_id', '=', $id)
                ->update(['is_suspend' => 1,
                        'suspend_start' => $request->suspend_start,
                        'suspend_end' => $request->suspend_end
                ]);

        if(Auth::user()->user_role != 3){
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User disabled temporary account for User ID USR-0" .  $id
            ]);
        }

        Alert::success('Success', 'Account Disabled Successfully.');

        return back();
    }

    public function disableAccount($id){

        UserStatus::where('user_id', '=', $id)
        ->update(['is_disable' => 1]);

        Alert::success('Success', 'Account Disabled Successfully.');

        if(Auth::user()->user_role != 3){
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User disabled account for User ID USR-0" .  $id
            ]);
        }

        return back();
    }

    public function enableAccount(User $user){
       
        $user =  UserStatus::where('user_id', '=', $user->user_id)->first();
    
        if($user->is_disable == 1){
            UserStatus::where('user_id', '=', $user->user_id)
            ->update(['is_disable' => 0]);
        }else{
            UserStatus::where('user_id', '=', $user->user_id)
            ->update(['is_suspend' => 0,
                        'suspend_start' => null,
                        'suspend_end' => null
            ]);
        }

        Alert::success('Success', 'Account Enabled Successfully.');

        if(Auth::user()->user_role != 3){
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User enabled account for User ID USR-0" . $user->user_id
            ]);
        }

        return back();
    }

    public function resetPassword(Request $request ,$id){

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            Alert::html('ERROR', 'Password reset unsuccessful !', 'error');
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $user = User::find($id);
        $user->password = Hash::make($request->password);

        if( $user->is_new_account == 1){
            $user->is_new_account = 0;
            $user->temp_password = '';
        }
           
        $user->save();

        Alert::success('Successful', 'Password reset successfully !');

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User reset password for User ID USR-0" . $id
        ]);

        return back()->with('message', ' Password reset successfully !');

    }

    public function myAccount(){

        $userLogin = Auth::user();
        
        return view('userpages/my-account', compact('userLogin')); 
    }

    public function editAccount(User $user){

        return view('userpages/accountmanagement/edit-account', compact('user')); 

    }

    public function editAccountSave(Request $request, $user){

        $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'birthday' => 'required', 
            'sex' => 'required', 
            'civil_status' => 'required', 
            'address' => 'required', 
            'phone_number' => 'required',
            'email' => 'email|required'
        ]);


        $user = User::with('barangay')->with('userStatus')->where('user_id', $user)->first();

        $user->email = $request->email;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->first_name = $request->first_name;
        $user->birthday = $request->birthday;
        $user->sex = $request->sex;
        $user->civil_status = $request->civil_status;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;

        $user->save();

        Alert::success('Message', 'Account Edit successfully.');

        if($user->user_id == Auth::user()->user_id){
            return redirect()->route('my.account');
        }
        if(Auth::user()->user_role == 3)
            return redirect()->route('view.oic.account', $user);
        else
            return redirect()->route('view.account', $user);


    }

    public function changePassword(Request $request){

        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', function ($attr, $password, $validation) use ($user) {
                if (!\Hash::check($password, $user->password)) {
                    return $validation(__('The current password is incorrect.'));
                }
            }],
            'new_password'     => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            Alert::html('ERROR', 'Password reset unsuccessful !', 'error');
            
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if(Hash::check($request->current_password, Auth::user()->password)){
            $userLogin = User::find(Auth::user()->user_id);
            $userLogin->password = Hash::make($request->new_password);

            if( $userLogin->is_new_account == 1){
                $userLogin->is_new_account = 0;
                $userLogin->temp_password = '';
            }
           
            $userLogin->save();
        }

        ActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'eventdetails' => "User changed his/her password."
        ]);

        Alert::success('Success', 'Password Change Successfully !');

        return redirect()->route('my.account');

    }
}
