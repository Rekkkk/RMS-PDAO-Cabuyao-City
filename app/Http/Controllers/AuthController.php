<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\UserStatus;
use App\Models\User;
use App\Models\Pwd;
use App\Models\PwdStatus;
use App\Models\Appointment;
use App\Models\ProgramStatus;
use App\Models\ActivityLog;
use Session;
use Validator;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\DemoMail;
use Illuminate\Support\Collection;


class AuthController extends Controller
{
    
    public function loginPage(){
       return view('landingpage/landingpages/login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return  $this->checkUser();
        }
        else{
            return redirect()->back()->withInput()->withErrors(['msg' => 'Invalid username or password']);
        }
    }

    public function checkUser(){

        if(Auth::check()){ 
           if(Auth::user()->userStatus->is_disable == 1){
                Session::flush();
                Auth::logout();
                return redirect()->back()->withInput()->withErrors(['msg' => "Your account is disabled. "]);
            }
            elseif(Auth::user()->userStatus->is_suspend == 1){      
                        
                $todayDate = date("Y-m-d");
                
                if($todayDate < Auth::user()->userStatus->suspend_start){

                    if(Auth::user()->user_role != 3){
                        ActivityLog::create([
                            'user_id' => Auth::user()->user_id,
                            'eventdetails' => "User logged in."
                        ]);
                    }   

                    if(Auth::user()->is_new_account == 1)
                        return redirect()->route('force.change.password');
                    else
                        return redirect()->route('dashboard');
                    
                }
                elseif($todayDate > Auth::user()->userStatus->suspend_end){
                    UserStatus::where('user_id', '=',  Auth::user()->user_id)
                                ->update(['is_suspend' => 0, 'suspend_start' => null, 'suspend_end' => null]);

                    ActivityLog::create([
                        'user_id' => Auth::user()->user_id,
                        'eventdetails' => "User logged in."
                    ]);

                    if(Auth::user()->is_new_account == 1)
                        return redirect()->route('force.change.password');
                    else
                        return redirect()->route('dashboard');

                    if(Auth::user()->user_role != 3){
                        ActivityLog::create([
                            'user_id' => Auth::user()->user_id,
                            'eventdetails' => "User logged in."
                        ]);
                    }
                }
                else{
                    $suspendEnd = Auth::user()->userStatus->suspend_end;
                    Session::flush();
                    Auth::logout();
                    return redirect()->back()->withInput()->withErrors(['msg' => "Your account is disabled until " . date('F d, Y', strtotime($suspendEnd)). "."]);
                }
            }
            else{
                if(Auth::user()->user_role != 3){
                    ActivityLog::create([
                        'user_id' => Auth::user()->user_id,
                        'eventdetails' => "User logged in."
                    ]);
                }

                if(Auth::user()->is_new_account == 1){
                    return redirect()->route('force.change.password');
                }else{
                    return redirect()->route('dashboard');
                }
            }
        }
        
        return redirect()->route('login');
    }

    public function forceChangePassword(){

        return view ('userpages/change-password');

    }

    public function dashboard(){
        if(Auth::user()->user_role == 3){
            return redirect()->route('account.management.oic');
        }
        elseif(Auth::user()->user_role == 1){
            return redirect()->route('pwd.management');
        }elseif(Auth::user()->user_role == 0){
            return redirect()->route('appointment.page');
        }
        else{
            $programs = ProgramStatus::all();
            $appointments = Appointment::all();
            $listPwd = Pwd::all();
            $pwdActive = new Collection;
            $pwdInactive = new Collection;
            $pwdCancelled = new Collection;

            $numOfActive = []; 
            $numOfInactive = []; 
            $numOfCancelled = []; 

            foreach($listPwd as $pwd){
                if($pwd->pwd_status->id_expiration > date("Y-m-d") && $pwd->pwd_status->cancelled == 0){
                    $pwdActive->push($pwd);
                }elseif($pwd->pwd_status->id_expiration < date("Y-m-d") && $pwd->pwd_status->cancelled == 0){
                    $pwdInactive->push($pwd);
                }elseif($pwd->pwd_status->cancelled == 1){
                    $pwdCancelled->push($pwd);
                }
            }     

            for($i = 2; $i < 20; $i++){
                $activeCounter = $pwdActive->where('barangay_id', $i)->count();
                $inActiveCounter = $pwdInactive->where('barangay_id', $i)->count();
                $cancelledCounter = $pwdCancelled->where('barangay_id', $i)->count();
                array_push($numOfActive, $activeCounter);
                array_push($numOfInactive, $inActiveCounter);
                array_push($numOfCancelled, $cancelledCounter);
            }
            
            $pwdStatus = PwdStatus::all();

            $admins = User::where('user_role', '1')->get();

            $numAdmin = 0;
            $numStaff = 0;

            foreach($admins as $admin){
                if($admin->userStatus->is_disable == 0)
                    $numAdmin++;
            }
            
            $staffs = User::where('user_role', '0')->get();

            foreach($staffs as $staff){
                if($staff->userStatus->is_disable == 0)
                    $numStaff++;
            }

            return view ('userpages/dashboard', compact('programs', 'numStaff', 'numOfActive', 'numOfActive', 'numOfInactive', 'numOfCancelled' ,'pwdStatus', 'numAdmin'));
        }

    }
  
    public function logOut(){

        if(Auth::user()->user_role != 3){
            ActivityLog::create([
                'user_id' => Auth::user()->user_id,
                'eventdetails' => "User Logged Out"
            ]); 
        }
           
        Session::flush();
        Auth::logout();

        return redirect ('/');

    }
}
