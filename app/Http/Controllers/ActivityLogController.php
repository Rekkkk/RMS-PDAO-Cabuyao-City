<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;


class ActivityLogController extends Controller
{
    
    public function activityLogPage(){

        $activityLogs = ActivityLog::with('user')->latest('log_id')->where('user_id', '!=', 1)->get();
        
        return view ('userpages/activitylog/activity-log', compact('activityLogs'));
    }
}
